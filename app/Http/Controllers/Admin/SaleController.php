<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Sale::with(['branch', 'user', 'warehouse']);

    // Filters
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('invoice_number', 'like', "%$s%")
              ->orWhere('customer_name', 'like', "%$s%")
              ->orWhere('customer_phone', 'like', "%$s%");
        });
    }
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }
    if ($request->filled('warehouse_id')) {
        $query->where('warehouse_id', $request->warehouse_id);
    }
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    // ============ PER PAGE ============
    $perPage = (int) $request->get('per_page', 20);
    if (!in_array($perPage, [10, 20, 50, 100])) {
        $perPage = 20;
    }

    $sales = $query->latest()->paginate($perPage);

    $branches = $user->hasRole('super_admin')
        ? \App\Models\Branch::pluck('name', 'id')
        : \App\Models\Branch::where('company_id', $user->company_id)->pluck('name', 'id');

    $warehouses = \App\Models\Warehouse::where('is_active', true)
        ->when(!$user->hasRole('super_admin'), function ($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })
        ->pluck('name', 'id');

    return view('admin.sales.index', compact('sales', 'branches', 'warehouses'));
}
    public function show(Sale $sale)
    {
        $sale->load(['items', 'branch', 'company', 'user', 'returns']);
        return view('admin.sales.show', compact('sale'));
    }

    public function profitReport(Request $request)
    {
        $query = Sale::with(['items', 'branch', 'user'])
            ->where('status', '!=', 'refunded');

        // Date filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $sales = $query->latest()->paginate(30);

        // Calculate totals for each sale
        $sales->getCollection()->transform(function ($sale) {
            $costTotal = 0;
            $profitTotal = 0;

            foreach ($sale->items as $item) {
                $qty = $item->is_return ? -$item->quantity : $item->quantity;
                $saleValue = $item->unit_price * $qty;
                $costValue = $item->cost_price * $qty;
                $profit = $saleValue - $costValue;

                $costTotal += $costValue;
                $profitTotal += $profit;
            }

            $sale->total_cost = $costTotal;
            $sale->total_profit = $profitTotal;
            $sale->margin_percent = $sale->total > 0 ? ($profitTotal / $sale->total * 100) : 0;

            return $sale;
        });

        // Summary totals
        $allSales = Sale::where('status', '!=', 'refunded');

        if ($request->filled('from_date')) {
            $allSales->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $allSales->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('branch_id')) {
            $allSales->where('branch_id', $request->branch_id);
        }

        $summary = [
            'total_sales' => 0,
            'total_cost' => 0,
            'total_profit' => 0,
        ];

        foreach ($allSales->with('items')->get() as $sale) {
            $summary['total_sales'] += $sale->total;

            foreach ($sale->items as $item) {
                $qty = $item->is_return ? -$item->quantity : $item->quantity;
                $summary['total_cost'] += $item->cost_price * $qty;
                $summary['total_profit'] += ($item->unit_price - $item->cost_price) * $qty;
            }
        }

        $summary['margin_percent'] = $summary['total_sales'] > 0
            ? ($summary['total_profit'] / $summary['total_sales'] * 100)
            : 0;

        $branches = auth()->user()->hasRole('super_admin')
            ? \App\Models\Branch::pluck('name', 'id')
            : \App\Models\Branch::where('company_id', auth()->user()->company_id)->pluck('name', 'id');

        return view('admin.sales.profit_report', compact('sales', 'summary', 'branches'));
    }

    public function destroy(Sale $sale)
    {
        // Restore stock
        foreach ($sale->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }

        $sale->delete();

        return redirect()->route('admin.sales.index')
            ->with('success', 'Sale deleted and stock restored.');
    }
    public function receipt(Sale $sale)
{
    $sale->load(['items', 'branch', 'company', 'user']);
    return view('admin.sales.receipt', compact('sale'));
}
}