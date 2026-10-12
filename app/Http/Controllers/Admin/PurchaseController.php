<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Purchase::with(['supplier', 'branch', 'warehouse', 'user']);

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('purchase_number', 'like', "%$s%")
              ->orWhere('supplier_invoice_no', 'like', "%$s%");
        });
    }
    if ($request->filled('from_date')) {
        $query->whereDate('purchase_date', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('purchase_date', '<=', $request->to_date);
    }
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }
    if ($request->filled('warehouse_id')) {
        $query->where('warehouse_id', $request->warehouse_id);
    }

    // ⚠️ PER PAGE
    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $purchases = $query->latest()->paginate($perPage);

    $suppliers = $user->hasRole('super_admin')
        ? \App\Models\Supplier::pluck('name', 'id')
        : \App\Models\Supplier::where('company_id', $user->company_id)->pluck('name', 'id');

    $branches = $user->hasRole('super_admin')
        ? \App\Models\Branch::pluck('name', 'id')
        : \App\Models\Branch::where('company_id', $user->company_id)->pluck('name', 'id');

    $warehouses = $user->hasRole('super_admin')
        ? \App\Models\Warehouse::pluck('name', 'id')
        : \App\Models\Warehouse::where('company_id', $user->company_id)->pluck('name', 'id');

    return view('admin.purchases.index', compact('purchases', 'suppliers', 'branches', 'warehouses'));
}

    public function create()
    {
        $user = auth()->user();

        $suppliers = $user->hasRole('super_admin')
            ? Supplier::pluck('name', 'id')
            : Supplier::where('company_id', $user->company_id)->pluck('name', 'id');

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        $warehouses = Warehouse::where('is_active', true)
            ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->pluck('name', 'id');

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price', 'cost_price', 'stock']);

        return view('admin.purchases.create', compact('suppliers', 'branches', 'warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_invoice_no' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank,credit',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $lineTotal = $item['unit_cost'] * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_cost' => $item['unit_cost'],
                    'quantity' => $item['quantity'],
                    'total' => $lineTotal,
                ];
            }

            $discount = $validated['discount'] ?? 0;
            $tax = $validated['tax'] ?? 0;
            $total = $subtotal - $discount + $tax;
            $paid = $validated['paid_amount'] ?? 0;
            $due = $total - $paid;

            $purchase = Purchase::create([
                'company_id' => $user->company_id,
                'branch_id' => $validated['branch_id'],
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'user_id' => $user->id,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'purchase_number' => $this->generatePurchaseNumber(),
                'supplier_invoice_no' => $validated['supplier_invoice_no'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paid,
                'due_amount' => $due,
                'payment_method' => $validated['payment_method'],
                'status' => 'received',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $index => $item) {
                $purchaseItem = $purchase->items()->create($item);

                // Create batch
                $batchNumber = 'BATCH-' . date('Ymd') . '-' . str_pad($purchase->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                ProductBatch::create([
                    'company_id' => $user->company_id,
                    'product_id' => $item['product_id'],
                    'purchase_id' => $purchase->id,
                    'purchase_item_id' => $purchaseItem->id,
                    'batch_number' => $batchNumber,
                    'unit_cost' => $item['unit_cost'],
                    'qty_in' => $item['quantity'],
                    'qty_remaining' => $item['quantity'],
                    'received_date' => $validated['purchase_date'],
                    'supplier_invoice_no' => $validated['supplier_invoice_no'] ?? null,
                ]);

                // Update product stock + cost
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);
                $product->update(['cost_price' => $item['unit_cost']]);

                // Update warehouse stock
                if (!empty($validated['warehouse_id'])) {
                    WarehouseStock::adjust($validated['warehouse_id'], $product->id, $item['quantity']);
                }
            }

            DB::commit();

            return redirect()->route('admin.purchases.show', $purchase)
                ->with('success', 'Purchase recorded. ' . count($itemsData) . ' batch(es) created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items', 'supplier', 'branch', 'warehouse', 'user', 'company']);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        try {
            DB::beginTransaction();

            foreach ($purchase->items as $item) {
                $item->product?->decrement('stock', $item->quantity);

                // Revert warehouse stock
                if ($purchase->warehouse_id) {
                    WarehouseStock::adjust($purchase->warehouse_id, $item->product_id, -$item->quantity);
                }
            }

            $purchase->delete();

            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Purchase deleted and stock reversed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function generatePurchaseNumber(): string
    {
        $prefix = 'PUR-' . date('Ymd') . '-';
        $last = Purchase::where('purchase_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->purchase_number, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}