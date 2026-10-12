@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">Profit Report</h1>
</div>
<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    {{-- SUMMARY CARDS --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;flex-shrink:0;">
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #2563eb;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Sales</div>
            <div style="font-size:22px;font-weight:800;color:#2563eb;margin-top:4px;">{{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #6b7280;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Cost</div>
            <div style="font-size:22px;font-weight:800;color:#374151;margin-top:4px;">{{ number_format($summary['total_cost'] ?? 0, 2) }}</div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #16a34a;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Total Profit</div>
            <div style="font-size:22px;font-weight:800;color:{{ ($summary['total_profit'] ?? 0) >= 0 ? '#16a34a' : '#dc2626' }};margin-top:4px;">
                {{ number_format($summary['total_profit'] ?? 0, 2) }}
            </div>
        </div>
        <div style="background:white;border-radius:12px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,0.04);border-left:4px solid #d97706;">
            <div style="color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Margin</div>
            <div style="font-size:22px;font-weight:800;color:{{ ($summary['margin_percent'] ?? 0) >= 0 ? '#16a34a' : '#dc2626' }};margin-top:4px;">
                {{ number_format($summary['margin_percent'] ?? 0, 1) }}%
            </div>
        </div>
    </div>

    {{-- TABLE — NO ACTIONS --}}
    <x-data-table
        search-placeholder="Search invoice or customer..."
        :export-pdf-route="auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'pdf'] + request()->query()) : null"
        :export-excel-route="auth()->user()->can('export.profit') ? route('admin.export.profit', ['format' => 'xlsx'] + request()->query()) : null"
        :refresh-route="route('admin.sales.profit-report')"
        :back-route="route('admin.sales.index')"
        back-label="← All Sales"
        :filters="[
            ['name' => 'from_date', 'type' => 'date'],
            ['name' => 'to_date', 'type' => 'date'],
            ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ]"
        :items="$sales"
        :columns="[
            ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
            ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
            ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
            ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
            ['field' => 'total', 'label' => 'Sale', 'type' => 'money', 'align' => 'right'],
            ['label' => 'Cost', 'align' => 'right', 'render' => fn($s) => '<span style=\'color:#6b7280;font-weight:600;\'>'.number_format($s->total_cost ?? 0, 2).'</span>'],
            ['label' => 'Profit', 'align' => 'right', 'render' => function($s) {
                $profit = $s->total_profit ?? 0;
                $color = $profit >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:700;color:'.$color.';font-size:14px;\'>'.number_format($profit, 2).'</span>';
            }],
            ['label' => 'Margin', 'align' => 'right', 'render' => function($s) {
                $margin = $s->margin_percent ?? 0;
                $color = $margin >= 0 ? '#16a34a' : '#dc2626';
                return '<span style=\'font-weight:600;color:'.$color.';\'>'.number_format($margin, 1).'%</span>';
            }],
        ]"
        :row-actions="[]"
        empty-icon="📈"
        empty-message="No sales found"
    />
</div>
@endsection