@extends('layouts.admin')

@section('content')
<x-data-table
    title="Suppliers"
    subtitle="Manage your suppliers"
    search-placeholder="Search name, company, phone, email..."
    :refresh-route="route('admin.suppliers.index')"
    :create-route="route('admin.suppliers.create')"
    create-label="➕ New Supplier"
    :items="$suppliers"
    :columns="[
        ['field' => 'name', 'label' => 'Name', 'type' => 'bold'],
        ['field' => 'company_name', 'label' => 'Company', 'type' => 'muted'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'email', 'label' => 'Email', 'type' => 'muted'],
        ['field' => 'opening_balance', 'label' => 'Opening', 'type' => 'money', 'align' => 'right'],
        ['field' => 'total_due', 'label' => 'Total Due', 'type' => 'money-red', 'align' => 'right'],
    ]"
    :row-actions="[
        ['route' => 'admin.suppliers.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.suppliers.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($s) {
            $url = route('admin.suppliers.destroy', $s);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this supplier?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="🚚"
    empty-message="No suppliers found"
    :empty-action-url="route('admin.suppliers.create')"
    empty-action-label="+ New Supplier"
/>
@endsection