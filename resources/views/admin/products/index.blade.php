@extends('layouts.admin')

@section('content')
<x-data-table
    title="Products"
    subtitle="Manage your products"
    search-placeholder="Search name or SKU..."
    :export-pdf-route="auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'pdf'] + request()->query()) : null"
    :export-excel-route="auth()->user()->can('export.inventory') ? route('admin.export.inventory', ['format' => 'xlsx'] + request()->query()) : null"
    :refresh-route="route('admin.products.index')"
    :create-route="route('admin.products.create')"
    create-label="➕ Add Product"
    :filters="[
        ['name' => 'category_id', 'placeholder' => 'All Categories', 'options' => $categories],
        ['name' => 'stock_status', 'placeholder' => 'All Stock', 'options' => ['out' => 'Out of Stock', 'low' => 'Low (1-10)', 'ok' => 'OK (>10)']],
    ]"
    :items="$products"
    :columns="[
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'cost_price', 'label' => 'Cost', 'type' => 'money', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
    ]"
    :row-actions="[
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="📦"
    empty-message="No products found"
    :empty-action-url="route('admin.products.create')"
    empty-action-label="+ Add Product"
/>
@endsection