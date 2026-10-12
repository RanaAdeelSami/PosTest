<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Console\Command;

class MigrateStockToWarehouses extends Command
{
    protected $signature = 'warehouses:migrate-stock';
    protected $description = 'Create default warehouses and migrate existing product stock';

    public function handle()
    {
        $this->info('=== MIGRATING STOCK TO WAREHOUSES ===');
        $this->newLine();

        $companies = Company::all();

        foreach ($companies as $company) {
            $this->info("Company: {$company->name}");

            // Create default warehouse if not exists
            $defaultWarehouse = Warehouse::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'is_default' => true,
                ],
                [
                    'name' => 'Main Store',
                    'code' => 'WH-MAIN',
                    'is_active' => true,
                ]
            );

            $this->line("  ✓ Default warehouse: {$defaultWarehouse->name}");

            // Create warehouse for each branch
            $branches = Branch::where('company_id', $company->id)->get();
            foreach ($branches as $branch) {
                $wh = Warehouse::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'branch_id' => $branch->id,
                    ],
                    [
                        'name' => $branch->name . ' Store',
                        'code' => 'WH-' . ($branch->code ?? $branch->id),
                        'is_active' => true,
                    ]
                );
                $this->line("  ✓ Branch warehouse: {$wh->name}");
            }

            // Migrate existing product stock to default warehouse
            $products = Product::where('company_id', $company->id)->get();
            $migrated = 0;

            foreach ($products as $product) {
                // Skip if already has warehouse stock
                $existing = WarehouseStock::where('product_id', $product->id)
                    ->where('quantity', '>', 0)
                    ->exists();

                if ($existing) {
                    continue;
                }

                if ($product->stock > 0) {
                    WarehouseStock::create([
                        'warehouse_id' => $defaultWarehouse->id,
                        'product_id' => $product->id,
                        'quantity' => $product->stock,
                    ]);
                    $migrated++;
                }
            }

            $this->info("  → Migrated {$migrated} product stocks to {$defaultWarehouse->name}");
            $this->newLine();
        }

        $this->info('✅ Stock migration complete!');
    }
}