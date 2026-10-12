@extends('layouts.admin')

@section('content')
<x-data-table
    title="Sales List"
    subtitle="Manage your sales"
    search-placeholder="Search invoice, customer, phone..."
    :export-pdf-route="auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'pdf'] + request()->query()) : null"
    :export-excel-route="auth()->user()->can('export.sales') ? route('admin.export.sales', ['format' => 'xlsx'] + request()->query()) : null"
    :refresh-route="route('admin.sales.index')"
    :create-route="route('admin.pos.index')"
    create-label="➕ New Sale"
    :filters="[
        ['name' => 'from_date', 'type' => 'date'],
        ['name' => 'to_date', 'type' => 'date'],
        ['name' => 'branch_id', 'placeholder' => 'All Branches', 'options' => $branches],
        ['name' => 'warehouse_id', 'placeholder' => 'All Warehouses', 'options' => $warehouses],
        ['name' => 'payment_method', 'placeholder' => 'All Payment', 'options' => ['cash' => 'Cash', 'card' => 'Card', 'credit' => 'Credit']],
    ]"
    :items="$sales"
    :columns="[
        ['field' => 'invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'warehouse.name', 'label' => 'Warehouse', 'type' => 'warehouse'],
        ['field' => 'user.name', 'label' => 'Cashier', 'type' => 'muted'],
        ['field' => 'total', 'label' => 'Total', 'type' => 'money', 'align' => 'right'],
        ['field' => 'payment_method', 'label' => 'Payment', 'type' => 'payment'],
    ]"
    :row-actions="[
        ['route' => 'admin.sales.show', 'icon' => '👁️', 'label' => 'View'],
    ]"
    empty-icon="📭"
    empty-message="No sales found"
/>
@endsection