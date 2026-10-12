<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super Admin → all
        if ($user->hasRole('super_admin')) {
            return;
        }

        // Company Head / Manager → all branches in their company
        if ($user->hasAnyRole(['company_head', 'manager'])) {
            return;
        }

        // Salesman → only their branch
        if ($user->hasRole('salesman') && $user->branch_id) {
            $builder->where($model->getTable() . '.branch_id', $user->branch_id);
        }
    }
}