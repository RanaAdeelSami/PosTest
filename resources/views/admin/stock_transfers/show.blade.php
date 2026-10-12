@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div>
        <h1 class="text-2xl font-bold">Transfer #{{ $stockTransfer->transfer_number }}</h1>
        <p class="text-sm text-gray-500">Date: {{ $stockTransfer->transfer_date->format('d M Y') }}</p>
    </div>
    <a href="{{ route('admin.stock-transfers.index') }}" class="text-blue-600">← All Transfers</a>
</div>

{{-- Route Visual --}}
<div class="bg-white p-5 rounded-lg shadow mb-4">
    <div class="flex items-center justify-between gap-4">
        <div class="flex-1 text-center p-3 bg-orange-50 rounded border border-orange-200">
            <div class="text-xs text-gray-500 uppercase">From</div>
            <div class="text-lg font-bold text-orange-700">🏭 {{ $stockTransfer->fromWarehouse->name ?? '-' }}</div>
        </div>
        <div class="text-3xl text-gray-400">→</div>
        <div class="flex-1 text-center p-3 bg-green-50 rounded border border-green-200">
            <div class="text-xs text-gray-500 uppercase">To</div>
            <div class="text-lg font-bold text-green-700">🏭 {{ $stockTransfer->toWarehouse->name ?? '-' }}</div>
        </div>
    </div>
</div>

{{-- Info --}}
<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Items</div>
        <div class="text-2xl font-bold">{{ $stockTransfer->items->count() }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Total Qty</div>
        <div class="text-2xl font-bold">{{ $stockTransfer->items->sum('quantity') }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Status</div>
        <div class="text-lg font-bold text-blue-600">{{ ucfirst($stockTransfer->status) }}</div>
    </div>
</div>

{{-- Items Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden mb-4">
    <table class="w-full text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-right">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockTransfer->items as $item)
                <tr class="border-t">
                    <td class="p-3">{{ $item->product_name }}</td>
                    <td class="p-3 text-right font-bold">{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-100">
            <tr>
                <td class="p-3 text-right font-bold">TOTAL:</td>
                <td class="p-3 text-right font-bold">{{ $stockTransfer->items->sum('quantity') }}</td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- Notes --}}
@if($stockTransfer->notes)
    <div class="bg-gray-50 p-4 rounded mb-4 text-sm">
        <strong>Notes:</strong> {{ $stockTransfer->notes }}
    </div>
@endif

{{-- Actions --}}
<div class="flex gap-2">
    <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
    <form method="POST" action="{{ route('admin.stock-transfers.destroy', $stockTransfer) }}" class="inline" onsubmit="return confirm('Reverse this transfer? Stock will be moved back.')">
        @csrf @method('DELETE')
        <button class="bg-red-600 text-white px-4 py-2 rounded">Reverse Transfer</button>
    </form>
</div>
@endsection