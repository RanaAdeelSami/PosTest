<?php

namespace App\Console\Commands;

use App\Models\Warehouse;
use Illuminate\Console\Command;

class WarehouseDetails extends Command
{
    protected $signature = 'warehouses:details';
    protected $description = 'Show warehouses with company info';

    public function handle()
    {
        $warehouses = Warehouse::withoutGlobalScopes()->with(['branch', 'company'])->get();

        $rows = [];
        foreach ($warehouses as $w) {
            $rows[] = [
                $w->id,
                $w->company->name ?? 'N/A',
                $w->name,
                $w->code ?? '-',
                $w->branch->name ?? 'None',
                $w->is_default ? 'YES' : 'no',
                $w->stocks()->sum('quantity'),
            ];
        }

        $this->table(
            ['ID', 'Company', 'Name', 'Code', 'Branch', 'Default', 'Stock'],
            $rows
        );
    }
}