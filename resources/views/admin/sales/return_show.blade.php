@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Return Receipt</h1>
            <p class="font-mono text-sm">{{ $return->return_number }}</p>
            <p class="text-sm">{{ $return->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Invoice:</p>
            <p class="font-mono">{{ $return->sale->invoice_number ?? '-' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
        <div>
            <p class="font-semibold">Customer:</p>
            <p>{{ $return->sale->customer_name ?? 'Walk-in' }}</p>
            <p>{{ $return->sale->customer_phone ?? '' }}</p>
        </div>
        <div class="text-right">
            <p class="font-semibold">Processed by:</p>
            <p>{{ $return->user->name ?? '-' }}</p>
            <p>Refund: {{ ucfirst($return->refund_method) }}</p>
        </div>
    </div>

    <table class="w-full mb-4 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Product</th>
                <th class="p-2 text-right">Price</th>
                <th class="p-2 text-right">Qty</th>
                <th class="p-2 text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($return->items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product_name }}</td>
                    <td class="p-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                    <td class="p-2 text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-right border-t pt-3">
        <span class="font-bold text-lg">Total Refund: </span>
        <span class="font-bold text-lg text-red-600">{{ number_format($return->return_amount, 2) }}</span>
    </div>

    @if($return->reason)
        <div class="mt-4 p-3 bg-gray-50 rounded text-sm">
            <strong>Reason:</strong> {{ $return->reason }}
        </div>
    @endif

    <div class="mt-6 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="{{ route('admin.sales.returns') }}" class="bg-gray-200 px-4 py-2 rounded">All Returns</a>
    </div>
</div>
@endsection