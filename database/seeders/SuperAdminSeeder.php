<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'company_id' => null,
                'branch_id' => null,
                'is_active' => true,
            ]
        );

        $admin->assignRole(UserRole::SUPER_ADMIN->value);
    }
}