<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'company_id', 'branch_id', 'user_id',
        'shift_number', 'opening_cash', 'closing_cash',
        'total_sales', 'cash_sales', 'card_sales', 'credit_sales',
        'expected_cash', 'difference',
        'opened_at', 'closed_at', 'status', 'notes',
    ];

    protected $casts = [
        'opening_cash' => 'decimal:2',
        'closing_cash' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'cash_sales' => 'decimal:2',
        'card_sales' => 'decimal:2',
        'credit_sales' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'difference' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function sales(): HasMany { return $this->hasMany(Sale::class); }

    public function getDurationAttribute(): string
    {
        if (!$this->closed_at) {
            return now()->diffForHumans($this->opened_at, true);
        }
        return $this->closed_at->diffForHumans($this->opened_at, true);
    }
}