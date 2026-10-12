<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierReturnItem extends Model
{
    protected $fillable = [
        'supplier_return_id', 'product_id',
        'product_name', 'unit_cost', 'quantity', 'total',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function supplierReturn(): BelongsTo { return $this->belongsTo(SupplierReturn::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}