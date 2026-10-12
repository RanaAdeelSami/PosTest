<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply if user is logged in AND not super admin
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super Admin sees everything
        if ($user->hasRole('super_admin')) {
            return;
        }

        // Company Head, Manager, Salesman → only their company
        if ($user->company_id) {
            $builder->where($model->getTable() . '.company_id', $user->company_id);
        }
    }
}