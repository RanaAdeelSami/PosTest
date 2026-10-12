<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Services\FifoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReturnController extends Controller
{
    protected FifoService $fifo;

    public function __construct(FifoService $fifo)
    {
        $this->fifo = $fifo;
    }
public function index()
{
    $perPage = (int) request()->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $returns = SaleReturn::with(['sale', 'branch', 'user'])
        ->latest()
        ->paginate($perPage);

    return view('admin.sales.returns', compact('returns'));
}
    public function create(Sale $sale)
    {
        $sale->load(['items.product', 'returns.items']);

        // Calculate already-returned quantities per sale_item_id
        $returnedQty = [];
        foreach ($sale->returns as $ret) {
            foreach ($ret->items as $retItem) {
                $key = $retItem->sale_item_id;
                $returnedQty[$key] = ($returnedQty[$key] ?? 0) + $retItem->quantity;
            }
        }

        return view('admin.sales.return', compact('sale', 'returnedQty'));
    }

    /**
     * Store a new sale return.
     */
    public function store(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string',
            'refund_method' => 'required|in:cash,card,credit',
            'items' => 'required|array|min:1',
            'items.*.sale_item_id' => 'required|exists:sale_items,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        $sale->load(['items', 'returns.items']);

        // Already returned quantities
        $returnedQty = [];
        foreach ($sale->returns as $ret) {
            foreach ($ret->items as $retItem) {
                $key = $retItem->sale_item_id;
                $returnedQty[$key] = ($returnedQty[$key] ?? 0) + $retItem->quantity;
            }
        }

        $user = auth()->user();

        try {
            DB::beginTransaction();

            $returnAmount = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $qty = (int) $item['quantity'];
                if ($qty <= 0) {
                    continue;
                }

                /** @var \App\Models\SaleItem $saleItem */
                $saleItem = $sale->items->firstWhere('id', $item['sale_item_id']);
                if (!$saleItem) {
                    throw new \Exception("Invalid sale item.");
                }

                $alreadyReturned = $returnedQty[$saleItem->id] ?? 0;
                $available = $saleItem->quantity - $alreadyReturned;

                if ($qty > $available) {
                    throw new \Exception("Cannot return {$qty} of {$saleItem->product_name}. Max available: {$available}");
                }

                // Line total based on original selling price
                $lineTotal = $saleItem->unit_price * $qty;
                $returnAmount += $lineTotal;

                // Use ORIGINAL cost snapshot from sale item
                $costPrice = (float) $saleItem->cost_price;

                $itemsData[] = [
                    'sale_item_id' => $saleItem->id,
                    'product_id' => $saleItem->product_id,
                    'product_name' => $saleItem->product_name,
                    'unit_price' => $saleItem->unit_price,
                    'cost_price' => $costPrice,
                    'quantity' => $qty,
                    'total' => $lineTotal,
                ];

                // 1. Increase product stock
                $saleItem->product?->increment('stock', $qty);

                // 2. Restore FIFO batches (reverse) — if sale was FIFO
                try {
                    $this->fifo->restoreToBatches($saleItem, $qty);
                } catch (\Exception $e) {
                    // If no batch history exists (legacy sale), skip silently
                    \Log::warning("Batch restore skipped for sale_item #{$saleItem->id}: " . $e->getMessage());
                }
            }

            if (empty($itemsData)) {
                throw new \Exception("Please select at least one item to return.");
            }

            // Create the sale return record
            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'company_id' => $sale->company_id,
                'branch_id' => $sale->branch_id,
                'user_id' => $user->id,
                'return_number' => $this->generateReturnNumber(),
                'return_amount' => $returnAmount,
                'reason' => $validated['reason'] ?? null,
                'refund_method' => $validated['refund_method'],
            ]);

            foreach ($itemsData as $item) {
                $saleReturn->items()->create($item);
            }

            // If fully returned → mark sale as refunded
            $this->updateSaleStatus($sale);

            DB::commit();

            return redirect()->route('admin.sales.return.show', $saleReturn)
                ->with('success', "Return processed successfully. Amount: " . number_format($returnAmount, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show single sale return receipt.
     */
    public function show(SaleReturn $return)
    {
        $return->load(['sale', 'items', 'branch', 'user', 'company']);
        return view('admin.sales.return_show', compact('return'));
    }

    /**
     * Reverse a return (delete → restore original state).
     */
    public function destroy(SaleReturn $return)
    {
        try {
            DB::beginTransaction();

            foreach ($return->items as $item) {
                // Find original sale item
                $saleItem = \App\Models\SaleItem::find($item->sale_item_id);
                if (!$saleItem) {
                    continue;
                }

                // Reverse stock
                $saleItem->product?->decrement('stock', $item->quantity);

                // Reverse batch consumption (re-consume from batches FIFO)
                try {
                    $this->fifo->consumeFifo($saleItem->product_id, $item->quantity);
                } catch (\Exception $e) {
                    \Log::warning("Batch reverse skipped for sale_item #{$saleItem->id}: " . $e->getMessage());
                }
            }

            $return->delete();

            DB::commit();

            return redirect()->route('admin.sales.index')
                ->with('success', 'Return reversed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Generate unique return number: RTN-YYYYMMDD-XXXX
     */
    private function generateReturnNumber(): string
    {
        $prefix = 'RTN-' . date('Ymd') . '-';

        $last = SaleReturn::where('return_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->return_number, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update sale status to 'refunded' if all items returned.
     */
    private function updateSaleStatus(Sale $sale): void
    {
        $sale->refresh();
        $sale->load(['items', 'returns.items']);

        $totalQty = $sale->items->sum('quantity');

        $returnedQty = 0;
        foreach ($sale->returns as $ret) {
            $returnedQty += $ret->items->sum('quantity');
        }

        if ($totalQty > 0 && $returnedQty >= $totalQty) {
            $sale->update(['status' => 'refunded']);
        }
    }
}