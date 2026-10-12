<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">

        @if($sale->type === 'exchange')
            <div class="mb-4 p-3 bg-yellow-100 border border-yellow-400 rounded">
                <strong class="text-yellow-800">EXCHANGE / RETURN</strong>
                @if($sale->originalSale)
                    — Original Invoice:
                    <a href="{{ route('admin.sales.show', $sale->originalSale) }}" class="text-blue-600 font-mono">
                        {{ $sale->originalSale->invoice_number }}
                    </a>
                @endif
            </div>
        @endif

        <div class="flex justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">{{ $sale->company->name ?? 'Company' }}</h1>
                <p class="text-sm text-gray-600">{{ $sale->branch->name ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $sale->company->phone ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $sale->company->address ?? '' }}</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold">
                    @if($sale->type === 'exchange')
                        EXCHANGE RECEIPT
                    @else
                        INVOICE
                    @endif
                </h2>
                <p class="text-sm font-mono">{{ $sale->invoice_number }}</p>
                <p class="text-sm">{{ $sale->created_at->format('d M Y H:i') }}</p>
                @if($sale->status === 'refunded')
                    <span class="inline-block mt-2 bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold">
                        REFUNDED
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="font-semibold">Customer:</p>
                <p>{{ $sale->customer_name ?? 'Walk-in' }}</p>
                <p>{{ $sale->customer_phone ?? '' }}</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">Cashier:</p>
                <p>{{ $sale->user->name ?? '-' }}</p>
                <p>Payment: {{ ucfirst($sale->payment_method) }}</p>
            </div>
        </div>

        <table class="w-full mb-4 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Item</th>
                    <th class="p-2 text-right">Price</th>
                    <th class="p-2 text-right">Qty</th>
                    <th class="p-2 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr class="border-t" style="{{ $item->is_return ? 'background:#fef3c7;' : '' }}">
                        <td class="p-2">
                            @if($item->is_return)
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs">RETURN</span>
                            @endif
                            {{ $item->product_name }}
                        </td>
                        <td class="p-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="p-2 text-right">{{ $item->quantity }}</td>
                        <td class="p-2 text-right">
                            @if($item->is_return)
                                <span class="text-red-600">-{{ number_format($item->total, 2) }}</span>
                            @else
                                {{ number_format($item->total, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-sm space-y-1 border-t pt-3">
            <div class="flex justify-end gap-4">
                <span class="w-32">Subtotal:</span>
                <span class="w-24 text-right">{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-end gap-4">
                <span class="w-32">Discount:</span>
                <span class="w-24 text-right">-{{ number_format($sale->discount, 2) }}</span>
            </div>
            <div class="flex justify-end gap-4">
                <span class="w-32">Tax:</span>
                <span class="w-24 text-right">{{ number_format($sale->tax, 2) }}</span>
            </div>
            <div class="flex justify-end gap-4 font-bold text-lg border-t pt-2">
                <span class="w-32">TOTAL:</span>
                <span class="w-24 text-right">{{ number_format($sale->total, 2) }}</span>
            </div>
        </div>

        @if($sale->notes)
            <div class="mt-4 p-3 bg-gray-50 rounded text-sm">
                <strong>Notes:</strong> {{ $sale->notes }}
            </div>
        @endif

        <div class="mt-8 text-center text-xs text-gray-500">
            Thank you for your business!
        </div>

        {{-- ADMIN BUTTONS (only visible on screen, not printed) --}}
        <div class="mt-6 flex gap-2 no-print flex-wrap">
            <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded">Print</button>
            <a href="{{ route('admin.sales.index') }}" class="bg-gray-200 px-4 py-2 rounded">Back to Sales</a>
            <a href="{{ route('admin.pos.index') }}" class="bg-green-600 text-white px-4 py-2 rounded">New Sale</a>
            @if($sale->status !== 'refunded')
                <a href="{{ route('admin.sales.return.create', $sale) }}" class="bg-red-600 text-white px-4 py-2 rounded">
                    Return / Refund
                </a>
            @endif
            @if($sale->status === 'refunded')
                <span class="bg-red-100 text-red-800 px-4 py-2 rounded font-bold">REFUNDED</span>
            @endif
            <a href="{{ route('admin.sales.receipt', $sale) }}" target="_blank"
   class="bg-purple-600 text-white px-4 py-2 rounded">
    🧾 Print Receipt
</a>
        </div>

        @if($sale->returns && $sale->returns->count() > 0)
            <div class="mt-6 border-t pt-4 no-print">
                <h3 class="font-bold mb-2">Returns for this sale:</h3>
                <ul class="text-sm space-y-1">
                    @foreach($sale->returns as $ret)
                        <li>
                            <a href="{{ route('admin.sales.return.show', $ret) }}" class="text-blue-600">
                                {{ $ret->return_number }}
                            </a>
                            — {{ number_format($ret->return_amount, 2) }} on {{ $ret->created_at->format('d M Y H:i') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>