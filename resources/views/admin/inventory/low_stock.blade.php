@extends('layouts.admin')

@section('content')
<x-data-table
    title="Low Stock Alerts"
    :subtitle="'Products with stock ≤ ' . $threshold"
    search-placeholder="Search product..."
    :refresh-route="route('admin.inventory.low-stock')"
    :back-route="route('admin.inventory.report')"
    back-label="📊 Full Report"
    :items="$products"
    :columns="[
        ['field' => 'name', 'label' => 'Product', 'type' => 'bold'],
        ['field' => 'sku', 'label' => 'SKU', 'type' => 'monospace'],
        ['field' => 'category.name', 'label' => 'Category'],
        ['field' => 'stock', 'label' => 'Stock', 'type' => 'stock', 'align' => 'right'],
        ['field' => 'price', 'label' => 'Price', 'type' => 'money', 'align' => 'right'],
    ]"
    :row-actions="[
        ['route' => 'admin.products.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($p) {
            $url = route('admin.products.destroy', $p);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this product?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="✅"
    empty-message="All products are well-stocked"
/>
@endsection