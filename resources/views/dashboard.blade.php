@extends('layouts.admin')
@section('body-class', 'scrollable') 
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="text-gray-600 text-sm">Welcome back, {{ auth()->user()->name }}!</p>
</div>

{{-- ============ ROW 1: MAIN CARDS ============ --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Today Sales --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Today Sales</div>
                <div class="text-2xl font-bold mt-2 text-blue-600">{{ number_format($todaySales, 2) }}</div>
                @php
                    $diff = $todaySales - $yesterdaySales;
                    $pct = $yesterdaySales > 0 ? ($diff / $yesterdaySales * 100) : ($todaySales > 0 ? 100 : 0);
                @endphp
                <div class="text-xs mt-1 {{ $diff >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    @if($diff >= 0) ▲ @else ▼ @endif
                    {{ number_format(abs($pct), 1) }}% vs yesterday
                </div>
            </div>
            <div class="text-3xl text-blue-200">💰</div>
        </div>
    </div>

    {{-- Month Sales --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">This Month</div>
                <div class="text-2xl font-bold mt-2 text-green-600">{{ number_format($monthSales, 2) }}</div>
                <div class="text-xs mt-1 text-gray-500">{{ now()->format('F Y') }}</div>
            </div>
            <div class="text-3xl text-green-200">📈</div>
        </div>
    </div>

    {{-- Low Stock --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Low Stock</div>
                <div class="text-2xl font-bold mt-2 {{ $lowStockCount > 0 ? 'text-yellow-600' : 'text-gray-600' }}">
                    {{ $lowStockCount }}
                </div>
                <div class="text-xs mt-1 text-gray-500">
                    {{ $outOfStockCount }} out of stock
                </div>
            </div>
            <div class="text-3xl text-yellow-200">⚠️</div>
        </div>
    </div>

    {{-- Pending Dues --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-gray-500 text-xs uppercase tracking-wide">Supplier Dues</div>
                <div class="text-2xl font-bold mt-2 {{ $pendingDues > 0 ? 'text-red-600' : 'text-gray-600' }}">
                    {{ number_format($pendingDues, 2) }}
                </div>
                <div class="text-xs mt-1 text-gray-500">Total payable</div>
            </div>
            <div class="text-3xl text-red-200">🧾</div>
        </div>
    </div>
</div>

{{-- ============ ROW 2: SALES TREND (7 days bar chart) ============ --}}
<div class="bg-white p-5 rounded-lg shadow mb-6">
    <h2 class="text-lg font-bold mb-4">Last 7 Days Sales</h2>

    @php
        $maxTrend = collect($salesTrend)->max('total') ?: 1;
    @endphp

    <div class="flex items-end justify-between gap-2 h-48">
        @foreach($salesTrend as $day)
            <div class="flex-1 flex flex-col items-center">
                <div class="text-xs text-gray-600 mb-1 font-medium">
                    {{ number_format($day['total'], 0) }}
                </div>
                <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition-all"
                     style="height: {{ $maxTrend > 0 ? max(5, ($day['total'] / $maxTrend) * 150) : 5 }}px; min-height: 5px;"
                     title="{{ $day['date'] }}: {{ number_format($day['total'], 2) }}">
                </div>
                <div class="text-xs text-gray-500 mt-2">{{ $day['day'] }}</div>
                <div class="text-xs text-gray-400">{{ $day['date'] }}</div>
            </div>
        @endforeach
    </div>
</div>

{{-- ============ ROW 3: RECENT SALES + TOP PRODUCTS ============ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    {{-- Recent Sales --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-bold">Recent Sales</h2>
            <a href="{{ route('admin.sales.index') }}" class="text-blue-600 text-sm">View All →</a>
        </div>

        @forelse($recentSales as $sale)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <div>
                    <a href="{{ route('admin.sales.show', $sale) }}" class="text-blue-600 font-mono text-xs">
                        {{ $sale->invoice_number }}
                    </a>
                    <div class="text-xs text-gray-500">
                        {{ $sale->customer_name ?? 'Walk-in' }} • {{ $sale->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm">{{ number_format($sale->total, 2) }}</div>
                    <div class="text-xs text-gray-500">{{ $sale->branch->name ?? '-' }}</div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 py-4 text-sm">No sales yet.</p>
        @endforelse
    </div>

    {{-- Top Products --}}
    <div class="bg-white p-5 rounded-lg shadow">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-bold">Top Products (This Month)</h2>
            <a href="{{ route('admin.products.index') }}" class="text-blue-600 text-sm">Products →</a>
        </div>

        @forelse($topProducts as $index => $product)
            <div class="flex justify-between items-center py-2 border-b last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <div class="text-sm font-medium">{{ $product->product_name }}</div>
                        <div class="text-xs text-gray-500">{{ $product->total_qty }} sold</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-sm">{{ number_format($product->total_amount, 2) }}</div>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 py-4 text-sm">No sales this month.</p>
        @endforelse
    </div>
</div>

{{-- ============ ROW 4: SYSTEM STATS ============ --}}
<div class="bg-white p-5 rounded-lg shadow">
    <h2 class="text-lg font-bold mb-4">System Overview</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @if(auth()->user()->hasRole('super_admin'))
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['companies'] }}</div>
                <div class="text-xs text-gray-500 uppercase mt-1">Companies</div>
            </div>
        @endif

        <div class="text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['branches'] }}</div>
            <div class="text-xs text-gray-500 uppercase mt-1">Branches</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['users'] }}</div>
            <div class="text-xs text-gray-500 uppercase mt-1">Users</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $stats['products'] }}</div>
            <div class="text-xs text-gray-500 uppercase mt-1">Products</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-pink-600">{{ $stats['categories'] }}</div>
            <div class="text-xs text-gray-500 uppercase mt-1">Categories</div>
        </div>

        <div class="text-center">
            <div class="text-2xl font-bold text-indigo-600">{{ $totalSalesCount }}</div>
            <div class="text-xs text-gray-500 uppercase mt-1">Total Sales</div>
        </div>
    </div>
</div>

@endsection