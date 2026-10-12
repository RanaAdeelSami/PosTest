<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\SupplierReturn;
use App\Models\SupplierReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierReturnController extends Controller
{
    public function index()
{
    $perPage = (int) request()->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $returns = SupplierReturn::with(['supplier', 'branch', 'user'])
        ->latest()
        ->paginate($perPage);

    return view('admin.supplier_returns.index', compact('returns'));
}

    public function create(Request $request)
    {
        $user = auth()->user();

        $suppliers = $user->hasRole('super_admin')
            ? Supplier::pluck('name', 'id')
            : Supplier::where('company_id', $user->company_id)->pluck('name', 'id');

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        $products = collect();

        $supplierProducts = [];
        $selectedSupplierId = $request->input('supplier_id') ?? old('supplier_id');

        if ($selectedSupplierId) {
            $supplierProducts = $this->getSupplierProductsWithStock((int) $selectedSupplierId);
        }

        $selectedPurchase = null;
        if ($request->filled('purchase_id')) {
            $selectedPurchase = Purchase::with('items')->find($request->purchase_id);
        }

        return view('admin.supplier_returns.create', compact(
            'suppliers',
            'branches',
            'products',
            'supplierProducts',
            'selectedPurchase',
            'selectedSupplierId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'branch_id' => 'nullable|exists:branches,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'return_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
            'settlement' => 'required|in:refund,credit_note,adjust_due',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        try {
            DB::beginTransaction();

            $total = 0;
            $itemsData = [];

            // Build lookup: [product_id => supplier product data]
            $supplierProducts = collect($this->getSupplierProductsWithStock((int) $validated['supplier_id']))
                ->keyBy('product_id');

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // 1. Product is supplier se khareeda gaya?
                $supplierData = $supplierProducts->get($product->id);
                if (!$supplierData) {
                    throw new \Exception("Product '{$product->name}' is supplier se nahi khareeda gaya. Return allowed nahi.");
                }

                // 2. Available qty check (purchased - already returned)
                $available = (int) $supplierData['available_qty'];
                if ($item['quantity'] > $available) {
                    throw new \Exception("'{$product->name}' ke liye max {$available} units return ho sakte hain. Aap ne {$item['quantity']} diye.");
                }

                // 3. Physical stock check
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("'{$product->name}' ka stock sirf {$product->stock} hai. Return nahi ho sakta.");
                }

                $lineTotal = $item['unit_cost'] * $item['quantity'];
                $total += $lineTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_cost' => $item['unit_cost'],
                    'quantity' => $item['quantity'],
                    'total' => $lineTotal,
                ];

                // Decrease stock (maal supplier ko wapas jaa raha hai)
                $product->decrement('stock', $item['quantity']);
            }

            if (empty($itemsData)) {
                throw new \Exception("Kam se kam ek item return karein.");
            }

            $supplierReturn = SupplierReturn::create([
                'company_id' => $user->company_id,
                'branch_id' => $validated['branch_id'] ?? null,
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $user->id,
                'purchase_id' => $validated['purchase_id'] ?? null,
                'return_number' => $this->generateReturnNumber(),
                'return_date' => $validated['return_date'],
                'total' => $total,
                'reason' => $validated['reason'] ?? null,
                'settlement' => $validated['settlement'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $item) {
                $supplierReturn->items()->create($item);
            }

            DB::commit();

            return redirect()->route('admin.supplier-returns.show', $supplierReturn)
                ->with('success', 'Supplier return recorded. Stock reduced by ' . count($itemsData) . ' item(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(SupplierReturn $supplierReturn)
    {
        $supplierReturn->load(['items', 'supplier', 'branch', 'user', 'company', 'purchase']);
        return view('admin.supplier_returns.show', compact('supplierReturn'));
    }

    public function destroy(SupplierReturn $supplierReturn)
    {
        try {
            DB::beginTransaction();

            // Reverse stock (maal wapas aaya)
            foreach ($supplierReturn->items as $item) {
                $item->product?->increment('stock', $item->quantity);
            }

            $supplierReturn->delete();

            DB::commit();

            return redirect()->route('admin.supplier-returns.index')
                ->with('success', 'Return reversed and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get supplier's purchased products with available return quantities.
     * Uses actual purchase_items.unit_cost (not product's cost_price).
     */
    private function getSupplierProductsWithStock(int $supplierId): array
    {
        $purchaseItems = PurchaseItem::query()
            ->whereHas('purchase', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->with('product')
            ->orderBy('id', 'desc')     // latest first so we pick most recent cost
            ->get();

        $grouped = [];

        foreach ($purchaseItems as $pi) {
            if (!$pi->product) {
                continue;
            }

            $pid = $pi->product_id;

            if (!isset($grouped[$pid])) {
                $grouped[$pid] = [
                    'product_id' => $pid,
                    'name' => $pi->product_name,
                    'sku' => $pi->product->sku,
                    'stock' => (int) $pi->product->stock,
                    'cost_price' => (float) $pi->unit_cost,   // ← ACTUAL purchase cost
                    'purchased_qty' => 0,
                    'returned_qty' => 0,
                    'available_qty' => 0,
                ];
            }

            $grouped[$pid]['purchased_qty'] += (int) $pi->quantity;
        }

        // Subtract already-returned quantities
        foreach ($grouped as $pid => $data) {
            $returned = SupplierReturnItem::query()
                ->where('product_id', $pid)
                ->whereHas('supplierReturn', function ($q) use ($supplierId) {
                    $q->where('supplier_id', $supplierId);
                })
                ->sum('quantity');

            $grouped[$pid]['returned_qty'] = (int) $returned;
            $grouped[$pid]['available_qty'] = max(0, $grouped[$pid]['purchased_qty'] - $grouped[$pid]['returned_qty']);
        }

        return array_values($grouped);
    }

    private function generateReturnNumber(): string
    {
        $prefix = 'SRT-' . date('Ymd') . '-';

        $last = SupplierReturn::where('return_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->return_number, -4)) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}