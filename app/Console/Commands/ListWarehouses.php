<?php

namespace App\Console\Commands;

use App\Models\Warehouse;
use Illuminate\Console\Command;

class ListWarehouses extends Command
{
    protected $signature = 'warehouses:list';
    protected $description = 'List all warehouses with details';

    public function handle()
    {
        $warehouses = Warehouse::with('branch')->get();

        $this->info('=== WAREHOUSES ===');
        $this->newLine();

        if ($warehouses->isEmpty()) {
            $this->warn('No warehouses found.');
            return;
        }

        $rows = [];
        foreach ($warehouses as $w) {
            $rows[] = [
                $w->id,
                $w->name,
                $w->code ?? '-',
                $w->branch->name ?? 'None',
                $w->is_default ? 'YES' : 'no',
                $w->is_active ? 'active' : 'inactive',
                $w->stocks()->sum('quantity'),
            ];
        }

        $this->table(
            ['ID', 'Name', 'Code', 'Branch', 'Default', 'Status', 'Stock Qty'],
            $rows
        );

        $this->newLine();
        $this->info('Total: ' . $warehouses->count() . ' warehouses');
    }
}