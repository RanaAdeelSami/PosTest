<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBatch extends Model
{
    protected $fillable = [
        'company_id', 'product_id', 'purchase_id', 'purchase_item_id',
        'batch_number', 'unit_cost', 'qty_in', 'qty_remaining',
        'received_date', 'supplier_invoice_no', 'notes',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'received_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function purchaseItem(): BelongsTo { return $this->belongsTo(PurchaseItem::class); }
    public function saleItemBatches(): HasMany { return $this->hasMany(SaleItemBatch::class); }
}