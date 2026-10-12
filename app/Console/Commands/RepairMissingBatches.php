<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\PurchaseItem;
use Illuminate\Console\Command;

class RepairMissingBatches extends Command
{
    protected $signature = 'batches:repair';
    protected $description = 'Create missing batches for products with stock but no remaining batch qty';

    public function handle()
    {
        $this->info('=== BATCH REPAIR REPORT ===');
        $this->newLine();

        $products = Product::where('stock', '>', 0)->get();
        $created = 0;
        $skipped = 0;

        foreach ($products as $product) {
            $batchQty = ProductBatch::where('product_id', $product->id)
                ->where('qty_remaining', '>', 0)
                ->sum('qty_remaining');

            // Report each product
            $this->line("Product: {$product->name}");
            $this->line("  Stock: {$product->stock}");
            $this->line("  Batch qty: {$batchQty}");

            if ($batchQty >= $product->stock) {
                $this->line("  Status: ✓ OK (no repair needed)");
                $this->newLine();
                $skipped++;
                continue;
            }

            $missingQty = $product->stock - $batchQty;

            // Get latest purchase cost
            $lastPurchaseItem = PurchaseItem::where('product_id', $product->id)
                ->orderBy('id', 'desc')
                ->first();

            $cost = $lastPurchaseItem->unit_cost
                ?? ($product->cost_price > 0 ? $product->cost_price : $product->price);

            // Create repair batch
            ProductBatch::create([
                'company_id' => $product->company_id,
                'product_id' => $product->id,
                'purchase_id' => $lastPurchaseItem->purchase_id ?? null,
                'purchase_item_id' => $lastPurchaseItem->id ?? null,
                'batch_number' => 'BATCH-REPAIR-' . $product->id . '-' . date('YmdHis'),
                'unit_cost' => $cost,
                'qty_in' => $missingQty,
                'qty_remaining' => $missingQty,
                'received_date' => now()->toDateString(),
                'supplier_invoice_no' => null,
                'notes' => 'Auto-created by batches:repair command',
            ]);

            $this->info("  Status: ✅ REPAIRED → +{$missingQty} units @ cost {$cost}");
            $this->newLine();
            $created++;
        }

        $this->newLine();
        $this->info("=== SUMMARY ===");
        $this->info("Products repaired: {$created}");
        $this->info("Products OK: {$skipped}");
        $this->info("Total products checked: " . $products->count());
    }
}