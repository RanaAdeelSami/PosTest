<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $companyId = $user->company_id;

        // Base queries with company filter
        $salesQuery = Sale::query();
        $productsQuery = Product::query();
        $usersQuery = User::query();
        $branchesQuery = Branch::query();
        $purchasesQuery = Purchase::query();

        if (!$isSuperAdmin && $companyId) {
            $salesQuery->where('company_id', $companyId);
            $productsQuery->where('company_id', $companyId);
            $usersQuery->where('company_id', $companyId);
            $branchesQuery->where('company_id', $companyId);
            $purchasesQuery->where('company_id', $companyId);
        }

        // ============ MAIN STATS ============

        $todaySales = (clone $salesQuery)
            ->whereDate('created_at', today())
            ->where('status', '!=', 'refunded')
            ->sum('total');

        $yesterdaySales = (clone $salesQuery)
            ->whereDate('created_at', today()->subDay())
            ->where('status', '!=', 'refunded')
            ->sum('total');

        $monthSales = (clone $salesQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'refunded')
            ->sum('total');

        $lowStockCount = (clone $productsQuery)
            ->where('is_active', true)
            ->where('stock', '<=', 10)
            ->count();

        $outOfStockCount = (clone $productsQuery)
            ->where('is_active', true)
            ->where('stock', '<=', 0)
            ->count();

        $pendingDues = Supplier::query()
            ->when(!$isSuperAdmin && $companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->get()
            ->sum('total_due');

        // ============ SECONDARY STATS ============

        $totalSalesCount = (clone $salesQuery)->count();
        $totalPurchaseAmount = (clone $purchasesQuery)->sum('total');

        // ============ RECENT SALES (last 5) ============

        $recentSales = (clone $salesQuery)
            ->with(['branch', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // ============ TOP PRODUCTS (this month, by qty sold) ============

        $topProducts = SaleItem::query()
            ->select('product_id', 'product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_amount'))
            ->whereHas('sale', function ($q) use ($isSuperAdmin, $companyId) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year)
                  ->where('status', '!=', 'refunded');

                if (!$isSuperAdmin && $companyId) {
                    $q->where('company_id', $companyId);
                }
            })
            ->where('is_return', false)
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ============ SALES TREND (last 7 days) ============

        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $total = (clone $salesQuery)
                ->whereDate('created_at', $date)
                ->where('status', '!=', 'refunded')
                ->sum('total');

            $salesTrend[] = [
                'date' => $date->format('d M'),
                'day' => $date->format('D'),
                'total' => $total,
            ];
        }

        // ============ SUMMARY STATS (for super admin / company head) ============

        $stats = [
            'companies' => $isSuperAdmin ? Company::count() : 0,
            'branches' => (clone $branchesQuery)->count(),
            'users' => (clone $usersQuery)->count(),
            'products' => (clone $productsQuery)->where('is_active', true)->count(),
            'categories' => Category::query()
                ->when(!$isSuperAdmin && $companyId, function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })
                ->count(),
        ];

        return view('dashboard', compact(
            'todaySales',
            'yesterdaySales',
            'monthSales',
            'lowStockCount',
            'outOfStockCount',
            'pendingDues',
            'totalSalesCount',
            'totalPurchaseAmount',
            'recentSales',
            'topProducts',
            'salesTrend',
            'stats'
        ));
    }
}