<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'company_id', 'name', 'email', 'phone', 'address',
        'company_name', 'opening_balance', 'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
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

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SupplierReturn::class);
    }

    public function getTotalDueAttribute(): float
    {
        $purchaseDue = $this->purchases()->sum('due_amount');
        $returnCredit = $this->returns()->sum('total');
        return (float) ($this->opening_balance + $purchaseDue - $returnCredit);
    }
}