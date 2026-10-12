<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $sale->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #f3f4f6;
            padding: 20px;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            background: white;
            padding: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .header .branch { font-size: 11px; }
        .header .info { font-size: 10px; color: #444; }

        .divider {
            border-top: 1px dashed #333;
            margin: 6px 0;
        }

        .divider-solid {
            border-top: 1px solid #333;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin: 2px 0;
        }

        table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        table th {
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding: 3px 0;
        }

        table td {
            padding: 2px 0;
            vertical-align: top;
        }

        table td.qty, table th.qty { text-align: center; width: 30px; }
        table td.price, table th.price { text-align: right; width: 55px; }
        table td.total, table th.total { text-align: right; width: 60px; }

        .totals {
            margin-top: 6px;
            font-size: 11px;
        }

        .totals .row { margin: 2px 0; }

        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            padding: 4px 0;
            margin: 4px 0;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 8px;
            color: #555;
        }

        .barcode {
            text-align: center;
            font-family: 'Libre Barcode 39', 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 2px;
            margin: 6px 0;
        }

        .invoice-number {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Print controls (hidden on print) */
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-print { background: #2563eb; color: white; }
        .btn-print:hover { background: #1d4ed8; }
        .btn-close { background: #e5e7eb; color: #374151; }
        .btn-close:hover { background: #d1d5db; }
        .btn-new { background: #16a34a; color: white; }
        .btn-new:hover { background: #15803d; }

        /* Auto print */
        @media print {
            body { background: white; padding: 0; }
            .receipt { box-shadow: none; width: 80mm; margin: 0; padding: 4px; }
            .controls { display: none !important; }
            @page { size: 80mm auto; margin: 2mm; }
        }
    </style>
</head>
<body>

    {{-- Print Controls --}}
    <div class="controls">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print</button>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-new">+ New Sale</a>
        <a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-close">📄 Full Invoice</a>
        <button class="btn btn-close" onclick="window.close()">✕ Close</button>
    </div>

    {{-- Receipt --}}
    <div class="receipt" id="receipt">

        {{-- Header --}}
        <div class="header center">
            <h1>{{ $sale->company->name ?? 'POS System' }}</h1>
            @if($sale->branch)
                <div class="branch">{{ $sale->branch->name }}</div>
            @endif
            @if($sale->company->phone)
                <div class="info">Tel: {{ $sale->company->phone }}</div>
            @endif
            @if($sale->company->address)
                <div class="info">{{ $sale->company->address }}</div>
            @endif
        </div>

        <div class="divider-solid"></div>

        {{-- Invoice info --}}
        <div class="row">
            <span>Invoice:</span>
            <span class="invoice-number">{{ $sale->invoice_number }}</span>
        </div>
        <div class="row">
            <span>Date:</span>
            <span>{{ $sale->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="row">
            <span>Cashier:</span>
            <span>{{ $sale->user->name ?? '-' }}</span>
        </div>

        @if($sale->customer_name || $sale->customer_phone)
            <div class="divider"></div>
            <div class="row">
                <span>Customer:</span>
                <span>{{ $sale->customer_name ?? 'Walk-in' }}</span>
            </div>
            @if($sale->customer_phone)
                <div class="row">
                    <span>Phone:</span>
                    <span>{{ $sale->customer_phone }}</span>
                </div>
            @endif
        @endif

        <div class="divider-solid"></div>

        {{-- Items --}}
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th class="price">Price</th>
                    <th class="total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                    <tr>
                        <td>
                            {{ $item->product_name }}
                            @if($item->is_return)
                                <span style="color: #dc2626;">[RET]</span>
                            @endif
                        </td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="price">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="total">
                            @if($item->is_return)
                                -{{ number_format($item->total, 2) }}
                            @else
                                {{ number_format($item->total, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-solid"></div>

        {{-- Totals --}}
        <div class="totals">
            <div class="row">
                <span>Subtotal:</span>
                <span>{{ number_format($sale->subtotal, 2) }}</span>
            </div>

            @if($sale->discount > 0)
                <div class="row">
                    <span>Discount:</span>
                    <span>-{{ number_format($sale->discount, 2) }}</span>
                </div>
            @endif

            @if($sale->tax > 0)
                <div class="row">
                    <span>Tax:</span>
                    <span>{{ number_format($sale->tax, 2) }}</span>
                </div>
            @endif

            <div class="grand-total row">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total, 2) }}</span>
            </div>

            <div class="row">
                <span>Payment:</span>
                <span>{{ strtoupper($sale->payment_method) }}</span>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Barcode-like invoice number --}}
        <div class="barcode">*{{ $sale->invoice_number }}*</div>

        {{-- Footer --}}
        <div class="footer">
            <div class="bold">Thank you for your business!</div>
            <div>Please come again</div>
            <div style="margin-top: 4px; font-size: 9px;">
                Software: {{ config('app.name') }}
            </div>
        </div>

        {{-- Refund notice --}}
        @if($sale->status === 'refunded')
            <div class="divider-solid"></div>
            <div class="center bold" style="color: #dc2626; font-size: 12px;">
                ** REFUNDED **
            </div>
        @endif

    </div>

    {{-- Auto print (optional) --}}
    <script>
        // Auto print after 500ms (agar URL me ?auto=1 ho)
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto') === '1') {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        }

        // Keyboard shortcut: Ctrl+P print
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>

</body>
</html>