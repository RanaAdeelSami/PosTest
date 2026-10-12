@extends('layouts.admin')

@section('content')
<x-data-table
    title="Sales Returns"
    subtitle="Customer return history"
    :refresh-route="route('admin.sales.returns')"
    :back-route="route('admin.sales.index')"
    back-label="← All Sales"
    :items="$returns"
    :columns="[
        ['field' => 'return_number', 'label' => 'Return #', 'type' => 'monospace'],
        ['field' => 'created_at', 'label' => 'Date', 'type' => 'datetime'],
        ['field' => 'sale.invoice_number', 'label' => 'Invoice', 'type' => 'monospace'],
        ['field' => 'sale.customer_name', 'label' => 'Customer', 'type' => 'bold'],
        ['field' => 'return_amount', 'label' => 'Amount', 'type' => 'money-red', 'align' => 'right'],
        ['field' => 'refund_method', 'label' => 'Refund', 'type' => 'badge', 'colors' => ['cash' => 'green', 'card' => 'blue', 'credit' => 'yellow']],
    ]"
    :row-actions="[
        ['route' => 'admin.sales.return.show', 'icon' => '👁️', 'label' => 'View'],
    ]"
    empty-icon="↩️"
    empty-message="No returns found"
/>
@endsection