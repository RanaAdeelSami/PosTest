@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold">Shift #{{ $shift->shift_number }}</h1>
    <p class="text-sm text-gray-500">
        Cashier: {{ $shift->user->name ?? '-' }} | Branch: {{ $shift->branch->name ?? '-' }}
    </p>
</div>

{{-- Status Banner --}}
@if($shift->status === 'open')
    <div class="bg-green-50 border border-green-200 text-green-800 p-3 rounded mb-4 flex justify-between items-center">
        <div>
            <strong>🟢 Shift is OPEN</strong>
            <p class="text-xs">Opened: {{ $shift->opened_at->format('d M Y H:i') }} ({{ $shift->duration }} ago)</p>
        </div>
        <a href="{{ route('admin.pos.index') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm">
            Go to POS →
        </a>
    </div>
@else
    <div class="bg-gray-100 border border-gray-300 text-gray-800 p-3 rounded mb-4">
        <strong>⚪ Shift CLOSED</strong>
        <p class="text-xs">Closed: {{ $shift->closed_at->format('d M Y H:i') }} | Duration: {{ $shift->duration }}</p>
    </div>
@endif

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Opening Cash</div>
        <div class="text-2xl font-bold text-blue-600">{{ number_format($shift->opening_cash, 2) }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Total Sales</div>
        <div class="text-2xl font-bold text-green-600">{{ number_format($shift->total_sales, 2) }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Expected Cash</div>
        <div class="text-2xl font-bold text-purple-600">{{ number_format($shift->expected_cash, 2) }}</div>
    </div>
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="text-gray-500 text-xs uppercase">Difference</div>
        @if($shift->status === 'closed')
            <div class="text-2xl font-bold {{ $shift->difference > 0 ? 'text-green-600' : ($shift->difference < 0 ? 'text-red-600' : 'text-gray-600') }}">
                {{ number_format($shift->difference, 2) }}
            </div>
        @else
            <div class="text-2xl font-bold text-gray-400">—</div>
        @endif
    </div>
</div>

{{-- Sales Breakdown --}}
<div class="bg-white p-5 rounded-lg shadow mb-6">
    <h2 class="text-lg font-bold mb-3">Sales Breakdown</h2>
    <div class="grid grid-cols-3 gap-4">
        <div class="text-center p-3 bg-green-50 rounded">
            <div class="text-xs text-gray-500 uppercase">Cash Sales</div>
            <div class="text-xl font-bold text-green-600">{{ number_format($shift->cash_sales, 2) }}</div>
        </div>
        <div class="text-center p-3 bg-blue-50 rounded">
            <div class="text-xs text-gray-500 uppercase">Card Sales</div>
            <div class="text-xl font-bold text-blue-600">{{ number_format($shift->card_sales, 2) }}</div>
        </div>
        <div class="text-center p-3 bg-yellow-50 rounded">
            <div class="text-xs text-gray-500 uppercase">Credit Sales</div>
            <div class="text-xl font-bold text-yellow-600">{{ number_format($shift->credit_sales, 2) }}</div>
        </div>
    </div>
</div>

{{-- Close Shift Form (only if open) --}}
@if($shift->status === 'open')
    <div class="bg-white p-5 rounded-lg shadow mb-6 border-2 border-red-200">
        <h2 class="text-lg font-bold mb-3 text-red-700">🔴 Close Shift</h2>
        <p class="text-sm text-gray-600 mb-3">
            Drawer ka actual cash count karein aur daalein. Difference automatically calculate hoga.
        </p>

        <form method="POST" action="{{ route('admin.shifts.close', $shift) }}" onsubmit="return confirm('Shift close karein? Ye action undo nahi ho sakti.')">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block mb-1 font-medium">Expected Cash (system)</label>
                    <input type="text" value="{{ number_format($shift->expected_cash, 2) }}"
                           class="w-full border rounded p-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Closing Cash (actual) *</label>
                    <input name="closing_cash" type="number" step="0.01" min="0"
                           value="{{ $shift->expected_cash }}"
                           class="w-full border rounded p-2 text-lg font-bold border-red-400" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Closing Notes</label>
                <textarea name="notes" rows="2" class="w-full border rounded p-2" placeholder="Koi note...">{{ $shift->notes }}</textarea>
            </div>

            <div class="flex justify-end">
                <button class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 font-bold">
                    🔒 Close Shift
                </button>
            </div>
        </form>
    </div>
@endif

{{-- Recent Sales --}}
<div class="bg-white p-5 rounded-lg shadow">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-bold">Recent Sales ({{ $shift->sales->count() }})</h2>
        <a href="{{ route('admin.sales.index') }}" class="text-blue-600 text-sm">All Sales →</a>
    </div>

    @if($shift->sales->count() > 0)
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Invoice</th>
                    <th class="p-2 text-left">Time</th>
                    <th class="p-2 text-left">Customer</th>
                    <th class="p-2 text-right">Total</th>
                    <th class="p-2 text-left">Payment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shift->sales as $sale)
                    <tr class="border-t">
                        <td class="p-2 font-mono text-xs">
                            <a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600">{{ $sale->invoice_number }}</a>
                        </td>
                        <td class="p-2">{{ $sale->created_at->format('H:i:s') }}</td>
                        <td class="p-2">{{ $sale->customer_name ?? 'Walk-in' }}</td>
                        <td class="p-2 text-right font-bold">{{ number_format($sale->total, 2) }}</td>
                        <td class="p-2">{{ ucfirst($sale->payment_method) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-gray-500 text-center py-4">No sales in this shift yet.</p>
    @endif
</div>

<div class="mt-4">
    <a href="{{ route('admin.shifts.index') }}" class="text-gray-600">← All Shifts</a>
</div>
@endsection