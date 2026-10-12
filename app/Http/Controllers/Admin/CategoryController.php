<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Category::with('company');

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('company_id') && $user->hasRole('super_admin')) {
        $query->where('company_id', $request->company_id);
    }

    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $categories = $query->latest()->paginate($perPage);

    $companies = $user->hasRole('super_admin')
        ? \App\Models\Company::pluck('name', 'id')
        : collect();

    return view('admin.categories.index', compact('categories', 'companies'));
}

    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('admin.categories.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
         if (!auth()->user()->hasRole('super_admin') && auth()->user()->company_id) {
        $validated['company_id'] = auth()->user()->company_id;
    }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $companies = Company::pluck('name', 'id');
        return view('admin.categories.edit', compact('category', 'companies'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}