<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'product_name',
        'unit_price', 'cost_price', 'quantity', 'total',
        'is_return',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'total' => 'decimal:2',
        'is_return' => 'boolean',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(SaleItemBatch::class);
    }

    public function getProfitAttribute(): float
    {
        $multiplier = $this->is_return ? -1 : 1;
        return $multiplier * (($this->unit_price - $this->cost_price) * $this->quantity);
    }
}