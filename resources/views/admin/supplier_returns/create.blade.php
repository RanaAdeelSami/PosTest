@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Supplier Return</h1>

<form method="POST" action="{{ route('admin.supplier-returns.store') }}" class="bg-white p-6 rounded-lg shadow" id="returnForm">
    @csrf

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block mb-1 font-medium">Supplier *</label>
            <select name="supplier_id" id="supplierSelect" class="w-full border rounded p-2" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $id => $name)
                    <option value="{{ $id }}" {{ $selectedSupplierId == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <div class="text-xs text-gray-500 mt-1">Supplier select karte hi uske products load honge.</div>
        </div>
        <div>
            <label class="block mb-1 font-medium">Branch</label>
            <select name="branch_id" class="w-full border rounded p-2">
                <option value="">-- Optional --</option>
                @foreach($branches as $id => $name)
                    <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1 font-medium">Return Date *</label>
            <input name="return_date" type="date" value="{{ old('return_date', date('Y-m-d')) }}" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block mb-1 font-medium">Settlement *</label>
            <select name="settlement" class="w-full border rounded p-2" required>
                <option value="adjust_due" {{ old('settlement') == 'adjust_due' ? 'selected' : '' }}>Adjust Due</option>
                <option value="refund" {{ old('settlement') == 'refund' ? 'selected' : '' }}>Cash Refund</option>
                <option value="credit_note" {{ old('settlement') == 'credit_note' ? 'selected' : '' }}>Credit Note</option>
            </select>
        </div>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Reason</label>
        <input name="reason" value="{{ old('reason') }}" placeholder="Damaged / Wrong / Expired..." class="w-full border rounded p-2">
    </div>

    <h3 class="font-bold mb-2">Items to Return</h3>

    <div id="noSupplierMsg" class="text-sm text-gray-600 p-4 bg-yellow-50 border border-yellow-200 rounded mb-4">
        Pehle <strong>Supplier</strong> select karein — phir uske products load honge.
    </div>

    <div style="overflow-x:auto;">
        <table class="w-full mb-4 text-xs" id="itemsTable" style="display:none;table-layout:fixed;">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left" style="width:20%;">Product</th>
                    <th class="p-2 text-right" style="width:10%;">Purchased</th>
                    <th class="p-2 text-right" style="width:9%;">Returned</th>
                    <th class="p-2 text-right" style="width:9%;">Available</th>
                    <th class="p-2 text-right" style="width:13%;">Unit Cost</th>
                    <th class="p-2 text-center" style="width:13%;">Return Qty</th>
                    <th class="p-2 text-right" style="width:13%;">Total</th>
                    <th class="p-2 text-right" style="width:5%;"></th>
                </tr>
            </thead>
            <tbody id="itemsBody"></tbody>
        </table>
    </div>

    <button type="button" id="addItemBtn" class="bg-blue-600 text-white px-3 py-1 rounded mb-4" style="display:none;">+ Add Item</button>

    <div class="text-right mb-4 text-lg">
        <strong>Total: </strong><span id="grandTotal" class="text-red-600 font-bold">0.00</span>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Notes</label>
        <textarea name="notes" rows="2" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
    </div>

    {{-- Buttons right-aligned --}}
    <div class="flex justify-end items-center gap-3 pt-4 border-t">
        <a href="{{ route('admin.supplier-returns.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</a>
        <button class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700" id="submitBtn" style="display:none;">Process Return</button>
    </div>
</form>

<script>
var supplierProducts = @json($supplierProducts ?? []);
var itemIndex = 0;

document.getElementById('supplierSelect').addEventListener('change', function() {
    var supplierId = this.value;
    if (!supplierId) {
        window.location.href = "{{ route('admin.supplier-returns.create') }}";
        return;
    }
    window.location.href = "{{ route('admin.supplier-returns.create') }}?supplier_id=" + supplierId;
});

function addRow() {
    if (supplierProducts.length === 0) {
        alert('Is supplier ka koi product available nahi hai return ke liye.');
        return;
    }

    var idx = itemIndex++;
    var options = '<option value="">-- Select --</option>';
    var hasAny = false;

    for (var i = 0; i < supplierProducts.length; i++) {
        var p = supplierProducts[i];
        if (p.available_qty <= 0) continue;
        hasAny = true;
        options += '<option value="' + p.product_id + '" data-cost="' + p.cost_price + '" data-available="' + p.available_qty + '">' + p.name + ' (' + p.available_qty + ')</option>';
    }

    if (!hasAny) {
        alert('Is supplier ke saare products already fully returned hain.');
        return;
    }

    var row = document.createElement('tr');
    row.className = 'border-t';
    row.innerHTML =
        '<td class="p-2" style="width:20%;">' +
            '<select name="items[' + idx + '][product_id]" class="w-full border rounded p-1 product-select text-xs" required>' + options + '</select>' +
        '</td>' +
        '<td class="p-2 text-right" style="width:10%;"><span class="purchased-cell">-</span></td>' +
        '<td class="p-2 text-right" style="width:9%;"><span class="returned-cell">-</span></td>' +
        '<td class="p-2 text-right font-bold" style="width:9%;"><span class="available-cell">-</span></td>' +
        '<td class="p-2 text-right" style="width:13%;">' +
            '<input type="number" name="items[' + idx + '][unit_cost]" step="0.01" min="0" value="0" class="w-full border rounded p-1 text-right cost-input text-xs" readonly>' +
        '</td>' +
        '<td class="p-2 text-center" style="width:13%;">' +
            '<input type="number" name="items[' + idx + '][quantity]" min="1" max="0" value="1" class="w-full border rounded p-1 text-center qty-input text-xs">' +
        '</td>' +
        '<td class="p-2 text-right font-bold" style="width:13%;"><span class="line-total">0.00</span></td>' +
        '<td class="p-2" style="width:5%;">' +
            '<div class="flex justify-end">' +
                '<button type="button" class="text-red-600 remove-row font-bold">x</button>' +
            '</div>' +
        '</td>';
    document.getElementById('itemsBody').appendChild(row);

    row.querySelector('.product-select').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        var cost = parseFloat(opt.getAttribute('data-cost')) || 0;
        var available = parseInt(opt.getAttribute('data-available')) || 0;

        var pid = this.value;
        var product = null;
        for (var j = 0; j < supplierProducts.length; j++) {
            if (supplierProducts[j].product_id == pid) {
                product = supplierProducts[j];
                break;
            }
        }

        if (product) {
            row.querySelector('.purchased-cell').textContent = product.purchased_qty;
            row.querySelector('.returned-cell').textContent = product.returned_qty;
            row.querySelector('.available-cell').textContent = product.available_qty;
        } else {
            row.querySelector('.purchased-cell').textContent = '-';
            row.querySelector('.returned-cell').textContent = '-';
            row.querySelector('.available-cell').textContent = '-';
        }

        row.querySelector('.cost-input').value = cost.toFixed(2);
        var qtyInput = row.querySelector('.qty-input');
        qtyInput.setAttribute('max', available);
        qtyInput.value = available > 0 ? 1 : 0;

        calcTotal();
    });
}

document.getElementById('addItemBtn').addEventListener('click', addRow);

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        calcTotal();
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty-input')) {
        var max = parseInt(e.target.getAttribute('max')) || 0;
        var val = parseInt(e.target.value) || 0;
        if (val > max) {
            alert('Maximum ' + max + ' units return ho sakte hain.');
            e.target.value = max;
        }
        if (val < 0) e.target.value = 0;
        calcTotal();
    }
});

function calcTotal() {
    var rows = document.querySelectorAll('#itemsBody tr');
    var total = 0;
    for (var i = 0; i < rows.length; i++) {
        var cost = parseFloat(rows[i].querySelector('.cost-input').value) || 0;
        var qty = parseInt(rows[i].querySelector('.qty-input').value) || 0;
        var line = cost * qty;
        rows[i].querySelector('.line-total').textContent = line.toFixed(2);
        total += line;
    }
    document.getElementById('grandTotal').textContent = total.toFixed(2);
}

if (supplierProducts.length > 0) {
    document.getElementById('itemsTable').style.display = 'table';
    document.getElementById('addItemBtn').style.display = 'inline-block';
    document.getElementById('submitBtn').style.display = 'inline-block';
    document.getElementById('noSupplierMsg').style.display = 'none';
    addRow();
}

document.getElementById('returnForm').addEventListener('submit', function(e) {
    var rows = document.querySelectorAll('#itemsBody tr');
    if (rows.length === 0) {
        e.preventDefault();
        alert('Kam se kam ek item add karein.');
        return false;
    }

    var hasItem = false;
    for (var i = 0; i < rows.length; i++) {
        var qty = parseInt(rows[i].querySelector('.qty-input').value) || 0;
        if (qty > 0) hasItem = true;
    }
    if (!hasItem) {
        e.preventDefault();
        alert('Kam se kam ek item ki quantity daalein.');
        return false;
    }
});
</script>
@endsection