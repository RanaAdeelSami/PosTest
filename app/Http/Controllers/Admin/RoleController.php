<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        // Super admin ko hide karo
        $roles = Role::with('permissions')
            ->where('name', '!=', 'super_admin')
            ->get();

        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->id] = $role->permissions->pluck('name')->toArray();
        }

        $roleStyle = [
            'company_head' => ['color' => 'yellow', 'icon' => '🏢', 'desc' => 'Company owner'],
            'manager' => ['color' => 'blue', 'icon' => '📊', 'desc' => 'Daily operations'],
            'salesman' => ['color' => 'purple', 'icon' => '💼', 'desc' => 'Point of sale'],
        ];

        $colorMap = [
            'yellow' => [
                'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200',
                'text' => 'text-yellow-900', 'text2' => 'text-yellow-700',
                'text3' => 'text-yellow-600', 'bar' => 'bg-yellow-500',
            ],
            'blue' => [
                'bg' => 'bg-blue-50', 'border' => 'border-blue-200',
                'text' => 'text-blue-900', 'text2' => 'text-blue-700',
                'text3' => 'text-blue-600', 'bar' => 'bg-blue-500',
            ],
            'purple' => [
                'bg' => 'bg-purple-50', 'border' => 'border-purple-200',
                'text' => 'text-purple-900', 'text2' => 'text-purple-700',
                'text3' => 'text-purple-600', 'bar' => 'bg-purple-500',
            ],
        ];

        return view('admin.roles.index', compact('roles', 'permissions', 'rolePermissions', 'roleStyle', 'colorMap'));
    }

    public function togglePermission(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
            'checked' => 'required|boolean',
        ]);

        // Safety: super_admin ko block karo
        if ($role->name === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin permissions cannot be changed.',
            ], 403);
        }

        $permission = $request->permission;
        $checked = (bool) $request->checked;

        if ($checked) {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }

        return response()->json([
            'success' => true,
            'total' => $role->permissions()->count(),
        ]);
    }
}