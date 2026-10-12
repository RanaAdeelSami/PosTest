<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{
    protected $fillable = [
        'company_id', 'category_id', 'name', 'sku',
        'price', 'stock', 'description', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function batches(): HasMany
{
    return $this->hasMany(ProductBatch::class);
}

/**
 * Get remaining batches (FIFO order).
 */
public function remainingBatches()
{
    return $this->batches()
        ->where('qty_remaining', '>', 0)
        ->orderBy('received_date', 'asc')
        ->orderBy('id', 'asc');
}
public function warehouseStocks(): HasMany
{
    return $this->hasMany(WarehouseStock::class);
}
/**
 * Recalculate product's total stock from all warehouses.
 */
public function recalculateTotalStock(): void
{
    $total = $this->warehouseStocks()->sum('quantity');
    $this->update(['stock' => $total]);
}
}