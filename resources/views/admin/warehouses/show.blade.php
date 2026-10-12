@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold">{{ $warehouse->name }}</h1>
        <p class="text-sm text-gray-500">
            {{ $warehouse->code }} | Branch: {{ $warehouse->branch->name ?? '-' }}
            @if($warehouse->is_default)
                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs ml-2">DEFAULT</span>
            @endif
        </p>
    </div>
    <a href="{{ route('admin.warehouses.index') }}" class="text-blue-600">← All Warehouses</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Products</div>
        <div class="text-2xl font-bold">{{ $warehouse->stocks->count() }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Total Stock</div>
        <div class="text-2xl font-bold">{{ $totalStock }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Stock Value</div>
        <div class="text-2xl font-bold text-blue-600">{{ number_format($totalValue, 2) }}</div>
    </div>
</div>

{{-- Stock List --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-left">SKU</th>
                <th class="p-3 text-right">Quantity</th>
                <th class="p-3 text-right">Price</th>
                <th class="p-3 text-right">Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($warehouse->stocks as $stock)
                <tr class="border-t">
                    <td class="p-3">{{ $stock->product->name ?? 'N/A' }}</td>
                    <td class="p-3 font-mono text-xs">{{ $stock->product->sku ?? '-' }}</td>
                    <td class="p-3 text-right font-bold">{{ $stock->quantity }}</td>
                    <td class="p-3 text-right">{{ number_format($stock->product->price ?? 0, 2) }}</td>
                    <td class="p-3 text-right">{{ number_format(($stock->quantity) * ($stock->product->price ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-6 text-center text-gray-500">No stock in this warehouse.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection