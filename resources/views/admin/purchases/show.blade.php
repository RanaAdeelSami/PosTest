@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Purchase</h1>
            <p class="font-mono text-sm">{{ $purchase->purchase_number }}</p>
            <p class="text-sm">{{ $purchase->purchase_date->format('d M Y') }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Supplier:</p>
            <p class="font-bold">{{ $purchase->supplier->name ?? 'N/A' }}</p>
            <p class="text-sm">{{ $purchase->supplier->phone ?? '' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
        <div>
            <p><strong>Branch:</strong> {{ $purchase->branch->name ?? '-' }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($purchase->payment_method) }}</p>
            <p><strong>Supplier Invoice:</strong> {{ $purchase->supplier_invoice_no ?? '-' }}</p>
        </div>
        <div class="text-right">
            <p><strong>Entered by:</strong> {{ $purchase->user->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>
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
            @foreach($purchase->items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product_name }}</td>
                    <td class="p-2 text-right">{{ number_format($item->unit_cost, 2) }}</td>
                    <td class="p-2 text-right">{{ $item->quantity }}</td>
                    <td class="p-2 text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-sm space-y-1 border-t pt-3">
        <div class="flex justify-end gap-4"><span class="w-32">Subtotal:</span><span class="w-24 text-right">{{ number_format($purchase->subtotal, 2) }}</span></div>
        <div class="flex justify-end gap-4"><span class="w-32">Discount:</span><span class="w-24 text-right">-{{ number_format($purchase->discount, 2) }}</span></div>
        <div class="flex justify-end gap-4"><span class="w-32">Tax:</span><span class="w-24 text-right">{{ number_format($purchase->tax, 2) }}</span></div>
        <div class="flex justify-end gap-4 font-bold text-lg border-t pt-2"><span class="w-32">TOTAL:</span><span class="w-24 text-right">{{ number_format($purchase->total, 2) }}</span></div>
        <div class="flex justify-end gap-4 text-green-600"><span class="w-32">Paid:</span><span class="w-24 text-right">{{ number_format($purchase->paid_amount, 2) }}</span></div>
        <div class="flex justify-end gap-4 text-red-600 font-bold"><span class="w-32">Due:</span><span class="w-24 text-right">{{ number_format($purchase->due_amount, 2) }}</span></div>
    </div>

    @if($purchase->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded text-sm"><strong>Notes:</strong> {{ $purchase->notes }}</div>
    @endif

    <div class="mt-6 flex gap-2 flex-wrap">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
        <a href="{{ route('admin.purchases.index') }}" class="bg-gray-200 px-4 py-2 rounded">All Purchases</a>
        <a href="{{ route('admin.supplier-returns.create', ['purchase_id' => $purchase->id]) }}" class="bg-red-600 text-white px-4 py-2 rounded">Return to Supplier</a>
    </div>
</div>
@endsection