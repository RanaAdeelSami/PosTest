@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Return Items — Invoice {{ $sale->invoice_number }}</h1>

<form method="POST" action="{{ route('admin.sales.return.store', $sale) }}" class="bg-white p-6 rounded-lg shadow">
    @csrf

    <div class="mb-4">
        <p class="text-sm text-gray-600">Customer: <strong>{{ $sale->customer_name ?? 'Walk-in' }}</strong></p>
        <p class="text-sm text-gray-600">Date: {{ $sale->created_at->format('d M Y H:i') }}</p>
        <p class="text-sm text-gray-600">Total: <strong>{{ number_format($sale->total, 2) }}</strong></p>
    </div>

    <table class="w-full mb-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Product</th>
                <th class="p-3 text-right">Unit Price</th>
                <th class="p-3 text-right">Sold</th>
                <th class="p-3 text-right">Already Returned</th>
                <th class="p-3 text-right">Available</th>
                <th class="p-3 text-center">Return Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                @php
                    $already = $returnedQty[$item->id] ?? 0;
                    $available = $item->quantity - $already;
                @endphp
                <tr class="border-t">
                    <td class="p-3">{{ $item->product_name }}</td>
                    <td class="p-3 text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="p-3 text-right">{{ $item->quantity }}</td>
                    <td class="p-3 text-right text-gray-500">{{ $already }}</td>
                    <td class="p-3 text-right font-bold">{{ $available }}</td>
                    <td class="p-3 text-center">
                        @if($available > 0)
                            <input type="hidden" name="items[{{ $loop->index }}][sale_item_id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $loop->index }}][quantity]"
                                   min="0" max="{{ $available }}" value="0"
                                   class="w-20 border rounded p-1 text-center return-qty"
                                   data-price="{{ $item->unit_price }}">
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="grid grid-cols-2 gap-4 max-w-2xl">
        <div>
            <label class="block mb-1 font-medium">Reason</label>
            <textarea name="reason" rows="2" class="w-full border rounded p-2">{{ old('reason') }}</textarea>
        </div>
        <div>
            <label class="block mb-1 font-medium">Refund Method *</label>
            <select name="refund_method" class="w-full border rounded p-2" required>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="credit">Credit</option>
            </select>
        </div>
    </div>

    <div class="mt-4 p-4 bg-blue-50 rounded">
        <div class="flex justify-between text-lg font-bold">
            <span>Return Amount:</span>
            <span id="returnAmount">0.00</span>
        </div>
    </div>

    <div class="mt-4">
        <button class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700"
                onclick="return confirm('Process this return?')">
            Process Return
        </button>
        <a href="{{ route('admin.sales.show', $sale) }}" class="ml-2 text-gray-600">Cancel</a>
    </div>
</form>

<script>
document.querySelectorAll('.return-qty').forEach(function(input) {
    input.addEventListener('input', function() {
        var total = 0;
        document.querySelectorAll('.return-qty').forEach(function(i) {
            var q = parseInt(i.value) || 0;
            var p = parseFloat(i.dataset.price) || 0;
            total += q * p;
        });
        document.getElementById('returnAmount').textContent = total.toFixed(2);
    });
});
</script>
@endsection