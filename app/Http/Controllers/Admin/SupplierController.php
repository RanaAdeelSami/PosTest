<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
{
    $query = Supplier::query();

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")
              ->orWhere('company_name', 'like', "%$s%")
              ->orWhere('phone', 'like', "%$s%")
              ->orWhere('email', 'like', "%$s%");
        });
    }

$perPage = (int) $request->get('per_page', 10);
if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;
$suppliers = $query->latest()->paginate($perPage);
    return view('admin.suppliers.index', compact('suppliers'));
}

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['purchases' => function ($q) {
            $q->latest()->limit(20);
        }]);
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}