@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow mb-4">
    <h1 class="text-2xl font-bold mb-2">{{ $supplier->name }}</h1>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p><strong>Company:</strong> {{ $supplier->company_name ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $supplier->phone ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $supplier->email ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $supplier->address ?? '-' }}</p>
        </div>
        <div class="text-right">
            <p><strong>Opening:</strong> {{ number_format($supplier->opening_balance, 2) }}</p>
            <p class="text-lg"><strong>Total Due:</strong> <span class="text-red-600">{{ number_format($supplier->total_due, 2) }}</span></p>
        </div>
    </div>
</div>

<h2 class="text-lg font-bold mb-2">Recent Purchases</h2>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 text-left">#</th>
                <th class="p-2 text-left">Date</th>
                <th class="p-2 text-right">Total</th>
                <th class="p-2 text-right">Paid</th>
                <th class="p-2 text-right">Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supplier->purchases as $p)
                <tr class="border-t">
                    <td class="p-2"><a href="{{ route('admin.purchases.show', $p) }}" class="text-blue-600">{{ $p->purchase_number }}</a></td>
                    <td class="p-2">{{ $p->purchase_date->format('d M Y') }}</td>
                    <td class="p-2 text-right">{{ number_format($p->total, 2) }}</td>
                    <td class="p-2 text-right">{{ number_format($p->paid_amount, 2) }}</td>
                    <td class="p-2 text-right text-red-600">{{ number_format($p->due_amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-4 text-center text-gray-500">No purchases yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    <a href="{{ route('admin.suppliers.index') }}" class="text-gray-600">← Back to Suppliers</a>
</div>
@endsection