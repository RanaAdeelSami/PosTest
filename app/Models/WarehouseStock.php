<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';

    protected $fillable = [
        'warehouse_id', 'product_id', 'quantity',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Add or subtract stock for a product in a warehouse.
     */
    public static function adjust(int $warehouseId, int $productId, int $delta): void
    {
        $stock = static::firstOrCreate(
            ['warehouse_id' => $warehouseId, 'product_id' => $productId],
            ['quantity' => 0]
        );

        $stock->increment('quantity', $delta);
    }
}