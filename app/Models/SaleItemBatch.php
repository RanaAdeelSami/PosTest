<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItemBatch extends Model
{
    protected $fillable = [
        'sale_item_id', 'product_batch_id',
        'quantity', 'unit_cost', 'total_cost',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function saleItem(): BelongsTo { return $this->belongsTo(SaleItem::class); }
    public function productBatch(): BelongsTo { return $this->belongsTo(ProductBatch::class); }
}