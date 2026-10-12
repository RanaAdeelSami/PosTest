<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // General categories list
        $categories = [
            'Electronics',
            'Mobile Phones',
            'Laptops & Computers',
            'Home Appliances',
            'Groceries',
            'Beverages',
            'Snacks & Biscuits',
            'Dairy Products',
            'Fruits & Vegetables',
            'Meat & Poultry',
            'Bakery',
            'Frozen Foods',
            'Clothing',
            'Footwear',
            'Bags & Accessories',
            'Jewelry',
            'Cosmetics',
            'Personal Care',
            'Baby Items',
            'Toys',
            'Stationery',
            'Books',
            'Sports Items',
            'Furniture',
            'Home Decor',
            'Kitchenware',
            'Cleaning Supplies',
            'Hardware',
            'Electrical Items',
            'Automotive',
            'Pet Supplies',
            'Health & Medicine',
            'Pharmacy',
            'Mobile Accessories',
            'Computer Accessories',
            'Gaming',
            'Musical Instruments',
            'Gift Items',
            'Seasonal Items',
            'Others',
        ];

        // Get all companies (or the first one)
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->warn('No companies found. Please create a company first.');
            return;
        }

        $inserted = 0;
        $skipped = 0;

        foreach ($companies as $company) {
            foreach ($categories as $categoryName) {
                // Check if already exists for this company
                $exists = Category::where('company_id', $company->id)
                    ->where('name', $categoryName)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                Category::create([
                    'company_id' => $company->id,
                    'name' => $categoryName,
                    'description' => null,
                    'is_active' => true,
                ]);

                $inserted++;
            }
        }

        $this->command->info("✅ Inserted: {$inserted} categories");
        $this->command->info("⏭️  Skipped (already exist): {$skipped}");
    }
}