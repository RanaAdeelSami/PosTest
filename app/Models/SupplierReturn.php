<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierReturn extends Model
{
    protected $fillable = [
        'company_id', 'branch_id', 'supplier_id', 'user_id', 'purchase_id',
        'return_number', 'return_date', 'total', 'reason', 'settlement', 'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function items(): HasMany { return $this->hasMany(SupplierReturnItem::class); }
}