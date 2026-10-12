<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Sale;
use App\Models\SaleItemBatch;
use App\Models\Shift;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\FifoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    protected FifoService $fifo;

    public function __construct(FifoService $fifo)
    {
        $this->fifo = $fifo;
    }

    public function index()
{
    $user = auth()->user();

    // Shift check
    $openShift = Shift::where('user_id', $user->id)
        ->where('status', 'open')
        ->first();

    if (!$openShift) {
        return redirect()->route('admin.shifts.create')
            ->with('error', 'POS use karne se pehle apna shift open karein.');
    }

    // ============ BRANCH LOGIC ============
    // Salesman: sirf apni branch (read-only)
    // Manager/Head/Admin: saari branches (change kar sakte hain)
    $canChangeBranch = $user->hasAnyRole(['super_admin', 'company_head', 'manager']);

    if ($canChangeBranch) {
        // Sab branches
        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');
    } else {
        // Salesman: sirf apni branch
        $branches = $user->branch_id
            ? Branch::where('id', $user->branch_id)->pluck('name', 'id')
            : collect();
    }

    // Single branch → auto-set
    $singleBranchId = $branches->count() === 1 ? $branches->keys()->first() : null;

    // ============ WAREHOUSE LOGIC ============
    $warehouseQuery = Warehouse::where('is_active', true);

    if (!$user->hasRole('super_admin')) {
        $warehouseQuery->where('company_id', $user->company_id);

        // Salesman: sirf uski branch ka warehouse
        if (!$canChangeBranch && $user->branch_id) {
            $warehouseQuery->where(function ($q) use ($user) {
                $q->where('branch_id', $user->branch_id)
                  ->orWhereNull('branch_id');
            });
        }
    }

    $warehouses = $warehouseQuery
        ->orderBy('is_default', 'desc')
        ->orderBy('name')
        ->get(['id', 'name', 'code', 'is_default', 'branch_id']);

    $defaultWarehouse = $warehouses->firstWhere('is_default', true) ?? $warehouses->first();

    // Products
    $products = Product::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'sku', 'price', 'stock']);

    // Attach warehouse stock
    if ($defaultWarehouse) {
        foreach ($products as $product) {
            $ws = WarehouseStock::where('warehouse_id', $defaultWarehouse->id)
                ->where('product_id', $product->id)
                ->first();
            $product->warehouse_stock = $ws ? $ws->quantity : 0;
        }
    }

    return view('admin.pos.index', compact(
        'products',
        'branches',
        'openShift',
        'warehouses',
        'defaultWarehouse',
        'singleBranchId',
        'canChangeBranch'
    ));
}

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'payment_method' => 'required|in:cash,card,credit',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'type' => 'nullable|in:sale,exchange',
            'original_invoice' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.is_return' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        // Shift check
        $openShift = Shift::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if (!$openShift) {
            return response()->json([
                'success' => false,
                'message' => 'Koi open shift nahi mila. Pehle shift open karein.',
            ], 422);
        }

        // Warehouse check
        $warehouse = Warehouse::find($validated['warehouse_id']);
        if (!$warehouse || !$warehouse->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse active nahi hai.',
            ], 422);
        }

        // Find original sale if exchange
        $originalSale = null;
        if (!empty($validated['original_invoice'])) {
            $originalSale = Sale::where('invoice_number', $validated['original_invoice'])->first();
        }

        try {
            DB::beginTransaction();

            $saleSubtotal = 0;
            $returnSubtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $isReturn = !empty($item['is_return']);
                $qty = (int) $item['quantity'];

                if ($isReturn) {
                    // ============ RETURN ============
                    // Increase warehouse stock
                    WarehouseStock::adjust($warehouse->id, $product->id, $qty);

                    // Also increase total product stock
                    $product->increment('stock', $qty);

                    // Restore batch
                    try {
                        $this->restoreToBatches($product->id, $qty);
                    } catch (\Exception $e) {
                        \Log::warning("Batch restore failed: " . $e->getMessage());
                    }

                    $lineTotal = $product->price * $qty;
                    $returnSubtotal += $lineTotal;

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'cost_price' => $product->cost_price,
                        'quantity' => $qty,
                        'is_return' => true,
                        'total' => $lineTotal,
                        '_batch_consumption' => [],
                    ];
                } else {
                    // ============ SALE ============
                    // Check warehouse stock
                    $ws = WarehouseStock::where('warehouse_id', $warehouse->id)
                        ->where('product_id', $product->id)
                        ->first();

                    $availableStock = $ws ? $ws->quantity : 0;

                    if ($availableStock < $qty) {
                        throw new \Exception("Insufficient stock for {$product->name} in {$warehouse->name}. Available: {$availableStock}");
                    }

                    // FIFO consume
                    try {
                        $batchConsumption = $this->fifo->consumeFifo($product->id, $qty);
                    } catch (\Exception $e) {
                        if (str_contains($e->getMessage(), 'Insufficient batch stock')) {
                            $this->createRepairBatch($product);
                            $batchConsumption = $this->fifo->consumeFifo($product->id, $qty);
                        } else {
                            throw $e;
                        }
                    }

                    $totalCost = 0;
                    foreach ($batchConsumption as $c) {
                        $totalCost += $c['total_cost'];
                    }
                    $weightedCost = $qty > 0 ? $totalCost / $qty : 0;

                    $lineTotal = $product->price * $qty;
                    $saleSubtotal += $lineTotal;

                    // Deduct from warehouse stock
                    WarehouseStock::adjust($warehouse->id, $product->id, -$qty);

                    // Deduct from total product stock
                    $product->decrement('stock', $qty);

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                        'cost_price' => $weightedCost,
                        'quantity' => $qty,
                        'is_return' => false,
                        'total' => $lineTotal,
                        '_batch_consumption' => $batchConsumption,
                    ];
                }
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $netSubtotal = $saleSubtotal - $returnSubtotal;
            $total = $netSubtotal - $discount + $tax;

            $sale = Sale::create([
                'company_id' => $user->company_id,
                'branch_id' => $validated['branch_id'],
                'user_id' => $user->id,
                'shift_id' => $openShift->id,
                'warehouse_id' => $warehouse->id,    // ← Save warehouse
                'invoice_number' => $this->generateInvoiceNumber($validated['type'] ?? 'sale'),
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'subtotal' => $netSubtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'type' => $validated['type'] ?? 'sale',
                'original_sale_id' => $originalSale?->id,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                $batchConsumption = $item['_batch_consumption'] ?? [];
                unset($item['_batch_consumption']);

                $saleItem = $sale->items()->create($item);

                foreach ($batchConsumption as $c) {
                    SaleItemBatch::create([
                        'sale_item_id' => $saleItem->id,
                        'product_batch_id' => $c['batch_id'],
                        'quantity' => $c['qty'],
                        'unit_cost' => $c['unit_cost'],
                        'total_cost' => $c['total_cost'],
                    ]);
                }
            }

            $this->updateShiftStats($openShift);

            DB::commit();

            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'redirect' => route('admin.sales.receipt', ['sale' => $sale, 'auto' => 1]),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function updateShiftStats(Shift $shift): void
    {
        $sales = Sale::where('shift_id', $shift->id)
            ->where('status', '!=', 'refunded')
            ->get();

        $totalSales = 0;
        $cashSales = 0;
        $cardSales = 0;
        $creditSales = 0;

        foreach ($sales as $s) {
            $totalSales += $s->total;
            if ($s->payment_method === 'cash') $cashSales += $s->total;
            elseif ($s->payment_method === 'card') $cardSales += $s->total;
            else $creditSales += $s->total;
        }

        $shift->update([
            'total_sales' => $totalSales,
            'cash_sales' => $cashSales,
            'card_sales' => $cardSales,
            'credit_sales' => $creditSales,
            'expected_cash' => $shift->opening_cash + $cashSales,
        ]);
    }

    private function createRepairBatch(Product $product): void
    {
        $batchQty = ProductBatch::where('product_id', $product->id)
            ->where('qty_remaining', '>', 0)
            ->sum('qty_remaining');

        $missingQty = $product->stock - $batchQty;

        if ($missingQty <= 0) return;

        ProductBatch::create([
            'company_id' => $product->company_id,
            'product_id' => $product->id,
            'purchase_id' => null,
            'purchase_item_id' => null,
            'batch_number' => 'BATCH-AUTO-' . $product->id . '-' . date('YmdHis'),
            'unit_cost' => $product->cost_price > 0 ? $product->cost_price : $product->price,
            'qty_in' => $missingQty,
            'qty_remaining' => $missingQty,
            'received_date' => now()->toDateString(),
            'notes' => 'Auto-created during sale',
        ]);
    }

    private function restoreToBatches(int $productId, int $qty): void
    {
        $batches = ProductBatch::where('product_id', $productId)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        if ($batches->isEmpty()) return;

        $batches->first()->increment('qty_remaining', $qty);
    }

    private function generateInvoiceNumber(string $type = 'sale'): string
    {
        $prefix = ($type === 'exchange' ? 'EXC-' : 'INV-') . date('Ymd') . '-';

        $last = Sale::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->invoice_number, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}