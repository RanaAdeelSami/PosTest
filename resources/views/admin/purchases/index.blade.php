@extends('layouts.admin')

@section('content')
<x-data-table
    title="Purchases"
    subtitle="Manage your purchases"
    search-placeholder="Search purchase # or inv #..."
    :export-pdf-route="auth()->user()->can('export.purchases') ? route('admin.export.purchases', ['format' => 'pdf'] + request()->query()) : null"
    :export-excel-route="auth()->user()->can('export.purchases') ? route('admin.export.purchases', ['format' => 'xlsx'] + request()->query()) : null"
    :refresh-route="route('admin.purchases.index')"
    :create-route="route('admin.purchases.create')"
    create-label="➕ New Purchase"
    :filters="[
        ['name' => 'from_date', 'type' => 'date'],
        ['name' => 'to_date', 'type' => 'date'],
        ['name' => 'supplier_id', 'placeholder' => 'All Suppliers', 'options' => $suppliers],
        ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ['name' => 'warehouse_id', 'placeholder' => 'All Warehouses', 'options' => $warehouses],
    ]"
    :items="$purchases"
    :columns="[
        ['field' => 'purchase_number', 'label' => 'Purchase #', 'type' => 'monospace'],
        ['field' => 'purchase_date', 'label' => 'Date', 'type' => 'date'],
        ['field' => 'supplier.name', 'label' => 'Supplier', 'type' => 'bold'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'warehouse.name', 'label' => 'Warehouse', 'type' => 'warehouse'],
        ['field' => 'total', 'label' => 'Total', 'type' => 'money', 'align' => 'right'],
        ['field' => 'paid_amount', 'label' => 'Paid', 'type' => 'money-green', 'align' => 'right'],
        ['field' => 'due_amount', 'label' => 'Due', 'type' => 'money-red', 'align' => 'right'],
    ]"
    :row-actions="[
        ['route' => 'admin.purchases.show', 'icon' => '👁️', 'label' => 'View'],
    ]"
    empty-icon="📥"
    empty-message="No purchases found"
    :empty-action-url="route('admin.purchases.create')"
    empty-action-label="+ New Purchase"
/>
@endsection