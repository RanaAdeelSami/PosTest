@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Stock Transfer</h1>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.stock-transfers.store') }}" class="bg-white p-6 rounded-lg shadow" id="transferForm">
    @csrf

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-1 font-medium">From Warehouse *</label>
            <select name="from_warehouse_id" id="fromWarehouse" class="w-full border rounded p-2 border-orange-300 bg-orange-50" required>
                <option value="">-- Source Warehouse --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ old('from_warehouse_id') == $w->id ? 'selected' : '' }}>
                        🏭 {{ $w->name }}{{ $w->is_default ? ' (Default)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">To Warehouse *</label>
            <select name="to_warehouse_id" id="toWarehouse" class="w-full border rounded p-2 border-green-300 bg-green-50" required>
                <option value="">-- Destination Warehouse --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ old('to_warehouse_id') == $w->id ? 'selected' : '' }}>
                        🏭 {{ $w->name }}{{ $w->is_default ? ' (Default)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Transfer Date *</label>
            <input name="transfer_date" type="date" value="{{ old('transfer_date', date('Y-m-d')) }}" class="w-full border rounded p-2" required>
        </div>
    </div>

    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm" id="infoBox">
        ℹ️ Pehle <strong>From Warehouse</strong> select karein, phir uske products load honge.
    </div>

    <h3 class="font-bold mb-2">Items to Transfer</h3>

    <table class="w-full mb-4" id="itemsTable" style="display:none;">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Product</th>
                <th class="p-2 text-right">Available</th>
                <th class="p-2 text-center">Transfer Qty</th>
                <th class="p-2"></th>
            </tr>
        </thead>
        <tbody id="itemsBody"></tbody>
    </table>

    <button type="button" id="addItemBtn" class="bg-blue-600 text-white px-3 py-1 rounded mb-4" style="display:none;">+ Add Item</button>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Notes</label>
        <textarea name="notes" rows="2" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
    </div>

    <div class="flex justify-end items-center gap-3 pt-3 border-t">
        <a href="{{ route('admin.stock-transfers.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700">Cancel</a>
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold" id="submitBtn" style="display:none;">
            🔄 Transfer Stock
        </button>
    </div>
</form>

<script>
var availableProducts = [];
var itemIndex = 0;

document.getElementById('fromWarehouse').addEventListener('change', function() {
    var warehouseId = this.value;

    if (!warehouseId) {
        document.getElementById('itemsTable').style.display = 'none';
        document.getElementById('addItemBtn').style.display = 'none';
        document.getElementById('submitBtn').style.display = 'none';
        document.getElementById('infoBox').innerHTML = 'ℹ️ Pehle <strong>From Warehouse</strong> select karein.';
        return;
    }

    document.getElementById('infoBox').innerHTML = '⏳ Loading products...';

    fetch("{{ route('admin.stock-transfers.get-products') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ warehouse_id: warehouseId })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            availableProducts = data.products;

            if (availableProducts.length === 0) {
                document.getElementById('infoBox').innerHTML = '⚠️ Is warehouse me koi stock nahi hai.';
                document.getElementById('itemsTable').style.display = 'none';
                document.getElementById('addItemBtn').style.display = 'none';
                document.getElementById('submitBtn').style.display = 'none';
                return;
            }

            document.getElementById('infoBox').innerHTML = '✅ ' + availableProducts.length + ' products available. <strong>+ Add Item</strong> click karein.';
            document.getElementById('itemsTable').style.display = 'table';
            document.getElementById('addItemBtn').style.display = 'inline-block';
            document.getElementById('submitBtn').style.display = 'inline-block';
            document.getElementById('itemsBody').innerHTML = '';
            itemIndex = 0;
            addRow();
        }
    })
    .catch(function(err) {
        document.getElementById('infoBox').innerHTML = '❌ Error: ' + err.message;
    });
});

function addRow() {
    var idx = itemIndex++;
    var options = '<option value="">-- Select Product --</option>';
    for (var i = 0; i < availableProducts.length; i++) {
        var p = availableProducts[i];
        options += '<option value="' + p.id + '" data-available="' + p.quantity + '">' + p.name + ' (' + p.quantity + ')</option>';
    }

    var row = document.createElement('tr');
    row.className = 'border-t';
    row.innerHTML =
        '<td class="p-2"><select name="items[' + idx + '][product_id]" class="w-full border rounded p-1 product-select" required>' + options + '</select></td>' +
        '<td class="p-2 text-right available-cell font-bold">-</td>' +
        '<td class="p-2 text-center"><input type="number" name="items[' + idx + '][quantity]" min="1" value="1" class="w-20 border rounded p-1 text-center qty-input"></td>' +
        '<td class="p-2 text-center"><button type="button" class="text-red-600 remove-row font-bold">x</button></td>';
    document.getElementById('itemsBody').appendChild(row);

    row.querySelector('.product-select').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        var available = parseInt(opt.getAttribute('data-available')) || 0;
        row.querySelector('.available-cell').textContent = available;
        row.querySelector('.qty-input').setAttribute('max', available);
    });
}

document.getElementById('addItemBtn').addEventListener('click', addRow);

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});

document.getElementById('transferForm').addEventListener('submit', function(e) {
    var from = document.getElementById('fromWarehouse').value;
    var to = document.getElementById('toWarehouse').value;

    if (!from || !to) {
        e.preventDefault();
        alert('From aur To warehouse select karein.');
        return false;
    }

    if (from === to) {
        e.preventDefault();
        alert('From aur To warehouse different hone chahiye.');
        return false;
    }

    var rows = document.querySelectorAll('#itemsBody tr');
    if (rows.length === 0) {
        e.preventDefault();
        alert('Kam se kam ek item add karein.');
        return false;
    }
});
</script>
@endsection