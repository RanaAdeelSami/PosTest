<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $query = Branch::with('company');

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")->orWhere('code', 'like', "%$s%");
        });
    }

    if ($request->filled('company_id') && $user->hasRole('super_admin')) {
        $query->where('company_id', $request->company_id);
    }

    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $branches = $query->latest()->paginate($perPage);

    $companies = $user->hasRole('super_admin')
        ? \App\Models\Company::pluck('name', 'id')
        : collect();

    return view('admin.branches.index', compact('branches', 'companies'));
}

    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('admin.branches.create', compact('companies'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'company_id' => 'required|exists:companies,id',
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
    ]);

    // Company Head / Manager: force their own company
    if (!auth()->user()->hasRole('super_admin') && auth()->user()->company_id) {
        $validated['company_id'] = auth()->user()->company_id;
    }

    Branch::create($validated);

    return redirect()->route('admin.branches.index')
        ->with('success', 'Branch created successfully.');
}

    public function show(Branch $branch)
    {
        return view('admin.branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $companies = Company::pluck('name', 'id');
        return view('admin.branches.edit', compact('branch', 'companies'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $branch->update($validated);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}