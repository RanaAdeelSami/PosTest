@extends('layouts.admin')

@section('content')
<x-data-table
    title="Warehouses"
    subtitle="Manage your warehouses"
    search-placeholder="Search warehouse name or code..."
    :refresh-route="route('admin.warehouses.index')"
    :create-route="route('admin.warehouses.create')"
    create-label="➕ New Warehouse"
    :items="$warehouses"
    :columns="[
        ['field' => 'name', 'label' => 'Warehouse', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['field' => 'stocks_count', 'label' => 'Products', 'align' => 'center'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($w) => $w->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]"
    :row-actions="[
        ['route' => 'admin.warehouses.show', 'icon' => '👁️', 'label' => 'View'],
        ['route' => 'admin.warehouses.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($w) {
            $url = route('admin.warehouses.destroy', $w);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this warehouse?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="🏭"
    empty-message="No warehouses found"
    :empty-action-url="route('admin.warehouses.create')"
    empty-action-label="+ New Warehouse"
/>
@endsection