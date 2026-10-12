@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Purchase</h1>

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('admin.purchases.store') }}" class="bg-white p-6 rounded-lg shadow">
    @csrf

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block mb-1 font-medium">Supplier</label>
            <select name="supplier_id" class="w-full border rounded p-2">
                <option value="">-- None --</option>
                @foreach($suppliers as $id => $name)
                    <option value="{{ $id }}" {{ old('supplier_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Branch *</label>
            <select name="branch_id" class="w-full border rounded p-2" required>
                <option value="">-- Select --</option>
                @foreach($branches as $id => $name)
                    <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Warehouse *</label>
            <select name="warehouse_id" class="w-full border rounded p-2 border-blue-400 bg-blue-50" required>
                <option value="">-- Select Warehouse --</option>
                @foreach($warehouses as $id => $name)
                    <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>🏭 {{ $name }}</option>
                @endforeach
            </select>
            <div class="text-xs text-gray-500 mt-1">Stock is warehouse me add hogi.</div>
        </div>

        <div>
            <label class="block mb-1 font-medium">Purchase Date *</label>
            <input name="purchase_date" type="date" value="{{ old('purchase_date', date('Y-m-d')) }}" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">Supplier Invoice #</label>
            <input name="supplier_invoice_no" value="{{ old('supplier_invoice_no') }}" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block mb-1 font-medium">Payment Method *</label>
            <select name="payment_method" class="w-full border rounded p-2" required>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="bank">Bank</option>
                <option value="credit">Credit</option>
            </select>
        </div>

        <div>
            <label class="block mb-1 font-medium">Paid Amount</label>
            <input name="paid_amount" type="number" step="0.01" value="{{ old('paid_amount', 0) }}" class="w-full border rounded p-2">
        </div>
    </div>

    <h3 class="font-bold mb-2">Items</h3>

    <table class="w-full mb-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">Product</th>
                <th class="p-2 text-right">Unit Cost</th>
                <th class="p-2 text-center">Qty</th>
                <th class="p-2 text-right">Total</th>
                <th class="p-2"></th>
            </tr>
        </thead>
        <tbody id="itemsBody"></tbody>
    </table>

    <button type="button" id="addItemBtn" class="bg-blue-600 text-white px-3 py-1 rounded mb-4">+ Add Item</button>

    <div class="grid grid-cols-3 gap-4 mb-4">
        <div>
            <label class="block mb-1 font-medium">Discount</label>
            <input name="discount" type="number" step="0.01" value="{{ old('discount', 0) }}" id="discountInput" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block mb-1 font-medium">Tax</label>
            <input name="tax" type="number" step="0.01" value="{{ old('tax', 0) }}" id="taxInput" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="block mb-1 font-medium">Subtotal</label>
            <input type="text" id="subtotalDisplay" readonly class="w-full border rounded p-2 bg-gray-100">
        </div>
    </div>

    <div class="text-right mb-4 text-xl">
        <strong>Grand Total: </strong><span id="grandTotal" class="text-blue-600 font-bold">0.00</span>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Notes</label>
        <textarea name="notes" rows="2" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
    </div>

    <div class="flex justify-end items-center gap-3 pt-3 border-t">
        <a href="{{ route('admin.purchases.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700">Cancel</a>
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Purchase</button>
    </div>
</form>

<script>
var products = @json($products);
var itemIndex = 0;

function addRow() {
    var idx = itemIndex++;
    var options = '<option value="">-- Select Product --</option>';
    for (var i = 0; i < products.length; i++) {
        options += '<option value="' + products[i].id + '" data-cost="' + (products[i].cost_price || 0) + '">' + products[i].name + ' (Stock: ' + products[i].stock + ')</option>';
    }

    var row = document.createElement('tr');
    row.className = 'border-t';
    row.innerHTML = `
        <td class="p-2">
            <select name="items[${idx}][product_id]" class="w-full border rounded p-1 product-select" required>
                ${options}
            </select>
        </td>
        <td class="p-2">
            <input type="number" name="items[${idx}][unit_cost]" step="0.01" min="0" value="0" class="w-24 border rounded p-1 text-right cost-input">
        </td>
        <td class="p-2 text-center">
            <input type="number" name="items[${idx}][quantity]" min="1" value="1" class="w-20 border rounded p-1 text-center qty-input">
        </td>
        <td class="p-2 text-right line-total">0.00</td>
        <td class="p-2 text-center">
            <button type="button" class="text-red-600 remove-row font-bold">x</button>
        </td>
    `;
    document.getElementById('itemsBody').appendChild(row);

    row.querySelector('.product-select').addEventListener('change', function() {
        var cost = this.options[this.selectedIndex].getAttribute('data-cost');
        if (cost && parseFloat(cost) > 0) {
            row.querySelector('.cost-input').value = parseFloat(cost).toFixed(2);
            calcTotals();
        }
    });
}

document.getElementById('addItemBtn').addEventListener('click', addRow);

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        calcTotals();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty-input') || e.target.classList.contains('cost-input') || e.target.id === 'discountInput' || e.target.id === 'taxInput') {
        calcTotals();
    }
});

function calcTotals() {
    var rows = document.querySelectorAll('#itemsBody tr');
    var subtotal = 0;
    rows.forEach(function(row) {
        var cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        var qty = parseInt(row.querySelector('.qty-input').value) || 0;
        var line = cost * qty;
        row.querySelector('.line-total').textContent = line.toFixed(2);
        subtotal += line;
    });
    var discount = parseFloat(document.getElementById('discountInput').value) || 0;
    var tax = parseFloat(document.getElementById('taxInput').value) || 0;
    var total = subtotal - discount + tax;
    document.getElementById('subtotalDisplay').value = subtotal.toFixed(2);
    document.getElementById('grandTotal').textContent = total.toFixed(2);
}

addRow();
</script>
@endsection