<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'user']);

        // Filters
        if ($request->filled('from_warehouse_id')) {
            $query->where('from_warehouse_id', $request->from_warehouse_id);
        }
        if ($request->filled('to_warehouse_id')) {
            $query->where('to_warehouse_id', $request->to_warehouse_id);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('transfer_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('transfer_date', '<=', $request->to_date);
        }

$perPage = (int) $request->get('per_page', 10);
if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;
$transfers = $query->latest()->paginate($perPage);
        $warehouses = Warehouse::where('is_active', true)
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })
            ->pluck('name', 'id');

        return view('admin.stock_transfers.index', compact('transfers', 'warehouses'));
    }

    public function create()
    {
        $user = auth()->user();

        $warehouses = Warehouse::where('is_active', true)
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'is_default']);

        return view('admin.stock_transfers.create', compact('warehouses'));
    }

    /**
     * AJAX: Get products with stock in a specific warehouse.
     */
    public function getWarehouseProducts(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $stocks = WarehouseStock::with('product')
            ->where('warehouse_id', $request->warehouse_id)
            ->where('quantity', '>', 0)
            ->get();

        $products = $stocks->map(function ($stock) {
            return [
                'id' => $stock->product->id,
                'name' => $stock->product->name,
                'sku' => $stock->product->sku,
                'quantity' => $stock->quantity,
                'price' => $stock->product->price,
            ];
        })->filter(function ($p) {
            return $p['name'] !== null;
        })->values();

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Validate stock availability in from_warehouse
            foreach ($validated['items'] as $item) {
                $stock = WarehouseStock::where('warehouse_id', $validated['from_warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                $available = $stock ? $stock->quantity : 0;

                if ($available < $item['quantity']) {
                    $product = Product::find($item['product_id']);
                    throw new \Exception("Insufficient stock for {$product->name}. Available in source warehouse: {$available}");
                }
            }

            // Create transfer record
            $transfer = StockTransfer::create([
                'company_id' => $user->company_id,
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_warehouse_id' => $validated['to_warehouse_id'],
                'user_id' => $user->id,
                'transfer_number' => $this->generateTransferNumber(),
                'transfer_date' => $validated['transfer_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            // Process items
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                // Deduct from source
                WarehouseStock::adjust($validated['from_warehouse_id'], $product->id, -$item['quantity']);

                // Add to destination
                WarehouseStock::adjust($validated['to_warehouse_id'], $product->id, $item['quantity']);

                // Save item
                $transfer->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.stock-transfers.show', $transfer)
                ->with('success', 'Stock transferred successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['items.product', 'fromWarehouse', 'toWarehouse', 'user', 'company']);
        return view('admin.stock_transfers.show', compact('stockTransfer'));
    }

    public function destroy(StockTransfer $stockTransfer)
    {
        try {
            DB::beginTransaction();

            // Reverse stock movement
            foreach ($stockTransfer->items as $item) {
                // Add back to source
                WarehouseStock::adjust($stockTransfer->from_warehouse_id, $item->product_id, $item->quantity);

                // Remove from destination
                WarehouseStock::adjust($stockTransfer->to_warehouse_id, $item->product_id, -$item->quantity);
            }

            $stockTransfer->delete();

            DB::commit();

            return redirect()->route('admin.stock-transfers.index')
                ->with('success', 'Transfer reversed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function generateTransferNumber(): string
    {
        $prefix = 'TRF-' . date('Ymd') . '-';
        $last = StockTransfer::where('transfer_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->transfer_number, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}