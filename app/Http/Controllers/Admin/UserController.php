<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
  public function index(Request $request)
{
    $user = auth()->user();
    $query = User::with(['company', 'branch']);

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%");
        });
    }

    if ($request->filled('role')) {
        $query->role($request->role);
    }

    if ($request->filled('company_id') && $user->hasRole('super_admin')) {
        $query->where('company_id', $request->company_id);
    }

    // ⚠️ PER PAGE
    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;

    $users = $query->latest()->paginate($perPage);

    $roles = \Spatie\Permission\Models\Role::pluck('name');
    $companies = $user->hasRole('super_admin')
        ? \App\Models\Company::pluck('name', 'id')
        : collect();

    return view('admin.users.index', compact('users', 'roles', 'companies'));
}
    public function create()
{
    $user = auth()->user();

    if ($user->hasRole('super_admin')) {
        $companies = Company::pluck('name', 'id');
        $branches = Branch::pluck('name', 'id');
    } else {
        $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        $branches = Branch::where('company_id', $user->company_id)->pluck('name', 'id');
    }

    $roles = \Spatie\Permission\Models\Role::pluck('name', 'name');
    return view('admin.users.create', compact('companies', 'branches', 'roles'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|exists:roles,name',
        ]);
         if (!auth()->user()->hasRole('super_admin') && auth()->user()->company_id) {
        $validated['company_id'] = auth()->user()->company_id;
    }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $validated['company_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $companies = Company::pluck('name', 'id');
        $branches = Branch::pluck('name', 'id');
        $roles = Role::pluck('name', 'name');
        return view('admin.users.edit', compact('user', 'companies', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|exists:roles,name',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id'] ?? null,
            'branch_id' => $validated['branch_id'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}