<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $query = Warehouse::with(['branch'])->withCount('stocks');

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")->orWhere('code', 'like', "%$s%");
        });
    }

    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $warehouses = $query->latest()->paginate($perPage);

    return view('admin.warehouses.index', compact('warehouses'));
}
    public function create()
    {
        $user = auth()->user();

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        return view('admin.warehouses.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
        ]);

        $user = auth()->user();
        $validated['company_id'] = $user->company_id;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_default'] = $request->has('is_default') ? true : false;

        if ($validated['is_default']) {
            Warehouse::where('company_id', $user->company_id)
                ->update(['is_default' => false]);
        }

        Warehouse::create($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['branch', 'stocks.product']);

        $totalStock = $warehouse->stocks()->sum('quantity');
        $totalValue = 0;

        foreach ($warehouse->stocks as $stock) {
            if ($stock->product) {
                $totalValue += $stock->quantity * $stock->product->price;
            }
        }

        return view('admin.warehouses.show', compact('warehouse', 'totalStock', 'totalValue'));
    }

    public function edit(Warehouse $warehouse)
    {
        $user = auth()->user();

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        return view('admin.warehouses.edit', compact('warehouse', 'branches'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'branch_id' => 'nullable|exists:branches,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['is_default'] = $request->has('is_default') ? true : false;

        if ($validated['is_default']) {
            Warehouse::where('company_id', $warehouse->company_id)
                ->where('id', '!=', $warehouse->id)
                ->update(['is_default' => false]);
        }

        $warehouse->update($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $hasStock = $warehouse->stocks()->where('quantity', '>', 0)->exists();

        if ($hasStock) {
            return back()->with('error', 'Cannot delete warehouse with existing stock. Transfer stock first.');
        }

        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }

    /**
     * JSON: Get product stock for a warehouse.
     */
    public function stockJson(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $stocks = WarehouseStock::where('warehouse_id', $request->warehouse_id)
            ->get();

        $data = [];
        foreach ($stocks as $stock) {
            $data[$stock->product_id] = $stock->quantity;
        }

        return response()->json([
            'success' => true,
            'stock' => $data,
        ]);
    }
}