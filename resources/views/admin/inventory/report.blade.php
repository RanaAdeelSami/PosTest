@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">Inventory Report</h1>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Products</div>
        <div class="text-2xl font-bold">{{ $summary['total_products'] }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Total Stock</div>
        <div class="text-2xl font-bold">{{ $summary['total_stock'] }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Out of Stock</div>
        <div class="text-2xl font-bold text-red-600">{{ $summary['out_of_stock'] }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Low Stock</div>
        <div class="text-2xl font-bold text-yellow-600">{{ $summary['low_stock'] }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase tracking-wide">Stock Value</div>
        <div class="text-2xl font-bold text-blue-600">{{ number_format($summary['stock_value'], 2) }}</div>
    </div>
</div>

{{-- Auto-filter form with export buttons --}}
<form method="GET" id="filterForm" class="bg-white p-4 rounded-lg shadow mb-4 flex flex-wrap gap-3 items-end">

    <div>
        <label class="block text-xs text-gray-500 mb-1">Search</label>
        <input name="search" id="searchInput" value="{{ request('search') }}"
               placeholder="Name / SKU"
               class="border rounded p-2 w-48">
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Category</label>
        <select name="category_id" class="border rounded p-2">
            <option value="">All Categories</option>
            @foreach($categories as $id => $name)
                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Stock Status</label>
        <select name="stock_status" class="border rounded p-2">
            <option value="">All</option>
            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low (1-10)</option>
            <option value="ok" {{ request('stock_status') == 'ok' ? 'selected' : '' }}>OK (>10)</option>
        </select>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Sort By</label>
        <select name="sort" class="border rounded p-2">
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
            <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stock (Low → High)</option>
            <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (High → Low)</option>
            <option value="value_asc" {{ request('sort') == 'value_asc' ? 'selected' : '' }}>Value (Low → High)</option>
            <option value="value_desc" {{ request('sort') == 'value_desc' ? 'selected' : '' }}>Value (High → Low)</option>
        </select>
    </div>

    @if(request()->hasAny(['search', 'category_id', 'stock_status', 'sort']))
        <a href="{{ route('admin.inventory.report') }}" class="text-gray-600 px-3 py-2 text-sm hover:text-gray-800">✕ Clear</a>
    @endif

    {{-- Export buttons — right side --}}
    @can('export.inventory')
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('admin.export.inventory', ['format' => 'xlsx'] + request()->query()) }}"
               class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700 whitespace-nowrap">📊 Excel</a>
            <a href="{{ route('admin.export.inventory', ['format' => 'pdf'] + request()->query()) }}"
               class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 whitespace-nowrap">📄 PDF</a>
        </div>
    @endcan
</form>

{{-- Products Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">SKU</th>
                <th class="p-3 text-left">Category</th>
                <th class="p-3 text-right">Cost</th>
                <th class="p-3 text-right">Price</th>
                <th class="p-3 text-right">Stock</th>
                <th class="p-3 text-right">Value</th>
                <th class="p-3 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $p->name }}</td>
                    <td class="p-3 font-mono text-xs text-gray-500">{{ $p->sku ?? '-' }}</td>
                    <td class="p-3">{{ $p->category->name ?? '-' }}</td>
                    <td class="p-3 text-right text-gray-600">{{ number_format($p->cost_price, 2) }}</td>
                    <td class="p-3 text-right">{{ number_format($p->price, 2) }}</td>
                    <td class="p-3 text-right font-bold {{ $p->stock <= 0 ? 'text-red-600' : ($p->stock <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                        {{ $p->stock }}
                    </td>
                    <td class="p-3 text-right">{{ number_format($p->stock * $p->price, 2) }}</td>
                    <td class="p-3 text-center">
                        @if($p->stock <= 0)
                            <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-bold">OUT</span>
                        @elseif($p->stock <= 10)
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-bold">LOW</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs">OK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">
                        @if(request()->hasAny(['search', 'category_id', 'stock_status']))
                            No products match your filters.
                            <a href="{{ route('admin.inventory.report') }}" class="text-blue-600">Clear filters</a>
                        @else
                            No products found.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->appends(request()->query())->links() }}</div>

@if($products->count() > 0)
    <div class="mt-2 text-xs text-gray-500 text-right">
        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
        @if(request()->hasAny(['search', 'category_id', 'stock_status']))
            (filtered)
        @endif
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('filterForm');
    if (!form) return;

    form.querySelectorAll('select, input[type="date"]').forEach(function(el) {
        el.addEventListener('change', function() { form.submit(); });
    });

    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        var timer = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() { form.submit(); }, 500);
        });
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(timer);
                form.submit();
            }
        });
    }
});
</script>
@endsection