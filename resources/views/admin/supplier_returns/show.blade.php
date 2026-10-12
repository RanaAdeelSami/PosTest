@extends('layouts.admin')
@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Supplier Return</h1>
            <p class="font-mono text-sm">{{ $supplierReturn->return_number }}</p>
            <p class="text-sm">{{ $supplierReturn->return_date->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Supplier:</p>
            <p class="font-bold">{{ $supplierReturn->supplier->name ?? '-' }}</p>
            <p class="text-sm">{{ $supplierReturn->supplier->phone ?? '' }}</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
        <div>
            <p><strong>Branch:</strong> {{ $supplierReturn->branch->name ?? '-' }}</p>
            <p><strong>Settlement:</strong> {{ ucfirst(str_replace('_', ' ', $supplierReturn->settlement)) }}</p>
            <p><strong>Reason:</strong> {{ $supplierReturn->reason ?? '-' }}</p>
        </div>
        <div class="text-right">
            <p><strong>Processed by:</strong> {{ $supplierReturn->user->name ?? '-' }}</p>
            @if($supplierReturn->purchase)
                <p><strong>Linked Purchase:</strong> {{ $supplierReturn->purchase->purchase_number }}</p>
            @endif
        </div>
    </div>
    <table class="w-full mb-4 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Product</th>
                <th class="p-2 text-right">Unit Cost</th>
                <th class="p-2 text-right">Qty</th>
                <th class="p-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplierReturn->items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product_name }}</td>
                    <td class="p-2 text-right">{{ number_format($item->unit_cost, 2) }}</td>
                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                    <td class="p-2 text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-right border-t pt-3">
        <span class="font-bold text-lg">Total: </span>
        <span class="font-bold text-lg text-red-600">{{ number_format($supplierReturn->total, 2) }}</span>
    </div>
    @if($supplierReturn->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded text-sm"><strong>Notes:</strong> {{ $supplierReturn->notes }}</div>
    @endif
    <div class="mt-6 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="{{ route('admin.supplier-returns.index') }}" class="bg-gray-200 px-4 py-2 rounded">All Returns</a>
    </div>
</div>
@endsection