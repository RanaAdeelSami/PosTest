<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Product::with('category');

    // Search
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%");
        });
    }

    // Category filter
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Stock status filter
    if ($request->filled('stock_status')) {
        if ($request->stock_status === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($request->stock_status === 'low') {
            $query->whereBetween('stock', [1, 10]);
        } elseif ($request->stock_status === 'ok') {
            $query->where('stock', '>', 10);
        }
    }

    // ⚠️ PER PAGE — default 10
    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) {
        $perPage = 10;
    }

    $products = $query->latest()->paginate($perPage);

    $categories = $user->hasRole('super_admin')
        ? \App\Models\Category::pluck('name', 'id')
        : \App\Models\Category::where('company_id', $user->company_id)->pluck('name', 'id');

    return view('admin.products.index', compact('products', 'categories'));
}
    public function create()
{
    $user = auth()->user();

    if ($user->hasRole('super_admin')) {
        $companies = Company::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
    } else {
        $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        $categories = Category::where('company_id', $user->company_id)->pluck('name', 'id');
    }

    return view('admin.products.create', compact('companies', 'categories'));
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'company_id' => 'required|exists:companies,id',
        'category_id' => 'nullable|exists:categories,id',
        'name' => 'required|string|max:255',
        'sku' => 'nullable|string|max:100',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
    ]);

    if (!auth()->user()->hasRole('super_admin') && auth()->user()->company_id) {
        $validated['company_id'] = auth()->user()->company_id;
    }

    Product::create($validated);

    return redirect()->route('admin.products.index')
        ->with('success', 'Product created successfully.');
}

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
            $user = auth()->user();

          if ($user->hasRole('super_admin')) {
        $companies = Company::pluck('name', 'id');
        $categories = Category::pluck('name', 'id');
         } else {
        $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        $categories = Category::where('company_id', $user->company_id)->pluck('name', 'id');
    }
        return view('admin.products.edit', compact('product', 'companies', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}