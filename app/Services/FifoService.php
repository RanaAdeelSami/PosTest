<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class FifoService
{
    /**
     * Consume stock from batches in FIFO order.
     *
     * @return array  list of ['batch_id', 'qty', 'unit_cost', 'total_cost']
     */
    public function consumeFifo(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0.");
        }

        $product = Product::findOrFail($productId);

        // Check total available
        $availableQty = ProductBatch::where('product_id', $productId)
            ->where('qty_remaining', '>', 0)
            ->sum('qty_remaining');

        if ($availableQty < $quantity) {
            throw new \Exception("Insufficient batch stock for {$product->name}. Available: {$availableQty}, Required: {$quantity}");
        }

        $consumed = [];
        $remaining = $quantity;

        // FIFO: oldest first
        $batches = ProductBatch::where('product_id', $productId)
            ->where('qty_remaining', '>', 0)
            ->orderBy('received_date', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $take = min($batch->qty_remaining, $remaining);

            $consumed[] = [
                'batch_id' => $batch->id,
                'qty' => $take,
                'unit_cost' => (float) $batch->unit_cost,
                'total_cost' => $take * (float) $batch->unit_cost,
            ];

            $batch->decrement('qty_remaining', $take);
            $remaining -= $take;
        }

        return $consumed;
    }

    /**
     * Restore stock to batches on return (reverse order — newest first).
     */
    public function restoreToBatches(SaleItem $saleItem, int $returnQty): array
    {
        $restored = [];
        $remaining = $returnQty;

        // Get original batch consumption for this sale item
        $originalBatches = $saleItem->batches()
            ->orderBy('id', 'desc') // newest first (reverse FIFO)
            ->get();

        foreach ($originalBatches as $sib) {
            if ($remaining <= 0) break;

            $restore = min($sib->quantity, $remaining);

            $batch = ProductBatch::find($sib->product_batch_id);
            if ($batch) {
                $batch->increment('qty_remaining', $restore);
                $restored[] = [
                    'batch_id' => $batch->id,
                    'qty' => $restore,
                    'unit_cost' => (float) $batch->unit_cost,
                ];
            }

            $remaining -= $restore;
        }

        return $restored;
    }

    /**
     * Get weighted average cost from remaining batches (for display).
     */
    public function getAverageCost(int $productId): float
    {
        $batches = ProductBatch::where('product_id', $productId)
            ->where('qty_remaining', '>', 0)
            ->get();

        if ($batches->isEmpty()) {
            return 0;
        }

        $totalValue = $batches->sum(fn($b) => $b->qty_remaining * (float) $b->unit_cost);
        $totalQty = $batches->sum('qty_remaining');

        return $totalQty > 0 ? $totalValue / $totalQty : 0;
    }
}