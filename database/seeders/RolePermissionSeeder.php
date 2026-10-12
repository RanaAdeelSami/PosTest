<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
    // Company
    'company.view', 'company.create', 'company.edit', 'company.delete',

    // Branch
    'branch.view', 'branch.create', 'branch.edit', 'branch.delete',

    // User
    'user.view', 'user.create', 'user.edit', 'user.delete',

    // Category
    'category.view', 'category.create', 'category.edit', 'category.delete',

    // Product
    'product.view', 'product.create', 'product.edit', 'product.delete',

    // Sale
    'sale.view', 'sale.create',

    // Inventory
    'supplier.view', 'supplier.create', 'supplier.edit', 'supplier.delete',
    'purchase.view', 'purchase.create',
    'stock.adjust',
    'supplier.return',

    // ============ EXPORT PERMISSIONS ============
    'export.sales',
    'export.purchases',
    'export.inventory',
    'export.profit',
];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Super Admin — all permissions
        $superAdmin = Role::firstOrCreate(['name' => UserRole::SUPER_ADMIN->value]);
        $superAdmin->syncPermissions(Permission::all());

        // Company Head — everything within their company
        $companyHead = Role::firstOrCreate(['name' => UserRole::COMPANY_HEAD->value]);
        $companyHead->syncPermissions([
            'branch.view', 'branch.create', 'branch.edit', 'branch.delete',
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'category.view', 'category.create', 'category.edit', 'category.delete',
            'product.view', 'product.create', 'product.edit', 'product.delete',
            'sale.view', 'sale.create', 'export.sales',
            'export.purchases','export.inventory','export.profit',
        ]);

        // Manager — manage products & view users
        $manager = Role::firstOrCreate(['name' => UserRole::MANAGER->value]);
        $manager->syncPermissions([
            'branch.view',
            'user.view',
            'category.view', 'category.create', 'category.edit',
            'product.view', 'product.create', 'product.edit',
            'sale.view', 'sale.create','export.sales', 'export.inventory',
        ]);

        // Salesman — read-only + create sales
        $salesman = Role::firstOrCreate(['name' => UserRole::SALESMAN->value]);
        $salesman->syncPermissions([
            'product.view',
            'category.view',
            'sale.view', 'sale.create',
        ]);
    }
}