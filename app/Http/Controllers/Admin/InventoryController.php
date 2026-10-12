<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
   public function lowStock(Request $request)
{
    $threshold = 10;

    $query = Product::where('is_active', true)
        ->where('stock', '<=', $threshold);

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%");
        });
    }

    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $products = $query->orderBy('stock', 'asc')->paginate($perPage);

    return view('admin.inventory.low_stock', compact('products', 'threshold'));
}

    public function stockReport(Request $request)
    {
        $user = auth()->user();

        // Base query
        $query = Product::with('category')->where('is_active', true);

        // Company scope (via CompanyScope global scope on Product)

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low') {
                $query->whereBetween('stock', [1, 10]);
            } elseif ($request->stock_status === 'ok') {
                $query->where('stock', '>', 10);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'value_asc':
                $query->orderByRaw('stock * price ASC');
                break;
            case 'value_desc':
                $query->orderByRaw('stock * price DESC');
                break;
            case 'name_asc':
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $products = $query->paginate(30);

        // Summary (respects filters)
        $summaryQuery = Product::where('is_active', true);

        if ($request->filled('search')) {
            $s = $request->search;
            $summaryQuery->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%");
            });
        }

        if ($request->filled('category_id')) {
            $summaryQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $summaryQuery->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low') {
                $summaryQuery->whereBetween('stock', [1, 10]);
            } elseif ($request->stock_status === 'ok') {
                $summaryQuery->where('stock', '>', 10);
            }
        }

        $summary = [
            'total_products' => (clone $summaryQuery)->count(),
            'total_stock' => (clone $summaryQuery)->sum('stock'),
            'out_of_stock' => (clone $summaryQuery)->where('stock', '<=', 0)->count(),
            'low_stock' => (clone $summaryQuery)->whereBetween('stock', [1, 10])->count(),
            'stock_value' => (clone $summaryQuery)->sum(DB::raw('stock * price')),
        ];

        // Categories for filter dropdown
        $categories = $user->hasRole('super_admin')
            ? Category::pluck('name', 'id')
            : Category::where('company_id', $user->company_id)->pluck('name', 'id');

        return view('admin.inventory.report', compact('products', 'summary', 'categories'));
    }
}