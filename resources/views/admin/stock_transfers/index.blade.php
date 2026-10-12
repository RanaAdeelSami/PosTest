@extends('layouts.admin')

@section('content')
<x-data-table
    title="Stock Transfers"
    subtitle="Move stock between warehouses"
    :refresh-route="route('admin.stock-transfers.index')"
    :create-route="route('admin.stock-transfers.create')"
    create-label="➕ New Transfer"
    :filters="[
        ['name' => 'from_date', 'type' => 'date'],
        ['name' => 'to_date', 'type' => 'date'],
        ['name' => 'from_warehouse_id', 'placeholder' => 'From: All', 'options' => $warehouses],
        ['name' => 'to_warehouse_id', 'placeholder' => 'To: All', 'options' => $warehouses],
    ]"
    :items="$transfers"
    :columns="[
        ['field' => 'transfer_number', 'label' => 'Transfer #', 'type' => 'monospace'],
        ['field' => 'transfer_date', 'label' => 'Date', 'type' => 'date'],
        ['label' => 'From', 'render' => fn($t) => '<span style=\'background:#ffedd5;color:#9a3412;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>🏭 '.e($t->fromWarehouse->name ?? '—').'</span>'],
        ['label' => 'To', 'render' => fn($t) => '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>🏭 '.e($t->toWarehouse->name ?? '—').'</span>'],
        ['label' => 'Items', 'align' => 'center', 'render' => fn($t) => '<span style=\'font-weight:700;color:#1a1a1a;\'>'.$t->items->count().'</span>'],
        ['field' => 'user.name', 'label' => 'By', 'type' => 'muted'],
        ['field' => 'status', 'label' => 'Status', 'type' => 'badge', 'colors' => ['completed' => 'green', 'pending' => 'yellow', 'cancelled' => 'red'], 'align' => 'center'],
    ]"
    :row-actions="[
        ['route' => 'admin.stock-transfers.show', 'icon' => '👁️', 'label' => 'View'],
    ]"
    empty-icon="🔄"
    empty-message="No transfers found"
    :empty-action-url="route('admin.stock-transfers.create')"
    empty-action-label="+ New Transfer"
/>
@endsection