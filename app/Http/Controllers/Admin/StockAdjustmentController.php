<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $adjustments = StockAdjustment::with(['product', 'user', 'branch'])
            ->latest()
            ->paginate(20);

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'stock']);

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        return view('admin.stock_adjustments.index', compact('adjustments', 'products', 'branches'));
    }

    public function create()
    {
        // No longer used — modal on index
        return redirect()->route('admin.stock-adjustments.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'nullable|exists:branches,id',
            'type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();
        $product = Product::findOrFail($validated['product_id']);

        if ($validated['type'] === 'remove' && $product->stock < $validated['quantity']) {
            return back()->with('error', "Cannot remove {$validated['quantity']} units. Available: {$product->stock}")->withInput();
        }

        try {
            DB::beginTransaction();

            if ($validated['type'] === 'add') {
                $product->increment('stock', $validated['quantity']);
            } else {
                $product->decrement('stock', $validated['quantity']);
            }

            StockAdjustment::create([
                'company_id' => $user->company_id,
                'branch_id' => $validated['branch_id'] ?? null,
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.stock-adjustments.index')
                ->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        return redirect()->route('admin.stock-adjustments.index');
    }

    public function edit(StockAdjustment $stockAdjustment)
    {
        return redirect()->route('admin.stock-adjustments.index');
    }

    public function update(Request $request, StockAdjustment $stockAdjustment)
    {
        return redirect()->route('admin.stock-adjustments.index');
    }

    public function destroy(StockAdjustment $stockAdjustment)
    {
        try {
            DB::beginTransaction();

            $product = $stockAdjustment->product;

            if ($stockAdjustment->type === 'add') {
                $product?->decrement('stock', $stockAdjustment->quantity);
            } else {
                $product?->increment('stock', $stockAdjustment->quantity);
            }

            $stockAdjustment->delete();

            DB::commit();

            return redirect()->route('admin.stock-adjustments.index')
                ->with('success', 'Adjustment reversed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}