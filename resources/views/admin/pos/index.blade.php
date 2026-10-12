@extends('layouts.admin')

@section('body-class', 'no-scroll')

@section('content')
<style>
    .pos-wrap {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 14px;
        height: 100%;
        max-height: 100%;
        min-height: 0;
        overflow: hidden;
    }

    /* ============ PRODUCTS PANEL ============ */
    .prod-panel {
        background: white;
        border-radius: 12px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 100%;
        min-height: 0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .prod-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-shrink: 0;
    }

    .prod-head h2 { font-size: 15px; font-weight: 700; }

    .mode-tabs {
        display: flex;
        background: #f3f4f6;
        border-radius: 8px;
        padding: 3px;
    }

    .mode-tab {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        border: none;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        font-family: inherit;
    }

    .mode-tab.on-sale { background: #16a34a; color: white; }
    .mode-tab.on-return { background: #d97706; color: white; }

    .search-bar {
        display: flex;
        gap: 6px;
        margin-bottom: 10px;
        flex-shrink: 0;
    }

    .search-bar input[type="text"]:first-of-type {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 12.5px;
        background: #fafafa;
        outline: none;
        font-family: inherit;
    }

    .search-bar input[type="text"]:first-of-type:focus {
        border-color: #ff8a3d;
        background: white;
    }

    .search-bar input#barcodeInput {
        width: 150px;
        padding: 8px 10px;
        border: 2px solid #ff8a3d;
        border-radius: 8px;
        font-size: 12px;
        font-family: monospace;
        background: #fff7ed;
        outline: none;
        font-weight: 600;
    }

    .search-bar button {
        padding: 8px 12px;
        background: #8b5cf6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    .prod-grid {
        flex: 1 1 auto;
        min-height: 0;
        height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
        gap: 8px;
        align-content: start;
        padding-right: 4px;
    }

    .prod-grid::-webkit-scrollbar { width: 6px; }
    .prod-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .prod-grid::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }

    .prod-card {
        background: white;
        border: 1.5px solid #f0f0f0;
        border-radius: 10px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.15s;
    }

    .prod-card:hover {
        border-color: #ff8a3d;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255,138,61,0.12);
    }

    .prod-card.oos {
        opacity: 0.5;
        cursor: not-allowed;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .prod-name {
        font-size: 12px;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.3;
        height: 32px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .prod-sku {
        font-size: 9.5px;
        color: #9ca3af;
        font-family: monospace;
        margin: 2px 0 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .prod-price { font-size: 13px; font-weight: 700; color: #ff8a3d; }

    .prod-stock {
        font-size: 9.5px;
        font-weight: 600;
        padding: 1px 5px;
        border-radius: 3px;
        display: inline-block;
        margin-top: 3px;
    }

    .prod-stock.ok { background: #dcfce7; color: #166534; }
    .prod-stock.low { background: #fef3c7; color: #92400e; }
    .prod-stock.out { background: #fee2e2; color: #991b1b; }

    /* ============ CART PANEL ============ */
    .cart-panel {
        background: white;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 100%;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .cart-panel::-webkit-scrollbar { width: 6px; }
    .cart-panel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .cart-panel::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }

    .cart-head {
        padding: 12px 14px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        background: white;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .cart-head h2 {
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .badge-count {
        background: #ff8a3d;
        color: white;
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
    }

    .clear-link {
        background: none;
        border: none;
        color: #dc2626;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
    }

    .exch-box {
        display: none;
        background: #fffbeb;
        border-bottom: 1px solid #fde68a;
        padding: 8px 14px;
        flex-shrink: 0;
    }

    .exch-box.show { display: block; }

    .exch-box label {
        font-size: 9.5px;
        font-weight: 700;
        color: #92400e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .exch-box input {
        width: 100%;
        padding: 5px 8px;
        border: 1px solid #fcd34d;
        border-radius: 6px;
        font-size: 11px;
        margin-top: 3px;
        margin-bottom: 4px;
        outline: none;
        font-family: inherit;
    }

    /* CART ITEMS */
    .cart-items {
        flex: 0 0 auto;
        overflow: visible;
        padding: 10px 14px;
    }

    .empty-cart {
        text-align: center;
        padding: 30px 12px;
        color: #9ca3af;
    }

    .empty-cart .big { font-size: 40px; opacity: 0.3; margin-bottom: 6px; }
    .empty-cart .msg { font-size: 13px; font-weight: 600; }
    .empty-cart .hint { font-size: 11px; margin-top: 3px; }

    .cart-item {
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .cart-item:last-child { border-bottom: none; }

    .cart-item.ret {
        background: #fffbeb;
        margin: 0 -14px;
        padding: 14px 14px;
    }

    .ci-top {
        display: flex;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 10px;
    }

    .ci-name {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.4;
    }

    .ci-price {
        font-size: 11.5px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .ci-remove {
        background: none;
        border: none;
        color: #dc2626;
        font-size: 20px;
        cursor: pointer;
        line-height: 1;
        padding: 0 6px;
        opacity: 0.6;
    }

    .ci-remove:hover { opacity: 1; }

    .ci-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .qty-group {
        display: flex;
        align-items: center;
        gap: 3px;
        background: #f3f4f6;
        border-radius: 7px;
        padding: 3px;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border: none;
        background: white;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 700;
        font-size: 14px;
        color: #4b5563;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .qty-btn:hover { background: #ff8a3d; color: white; }

    .qty-num {
        width: 28px;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
    }

    .ci-total {
        font-size: 15px;
        font-weight: 700;
        color: #ff8a3d;
    }

    .ci-total.ret { color: #dc2626; }

    .return-tag {
        background: #dc2626;
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 3px;
        margin-right: 4px;
    }

    /* TOTALS */
    .totals {
        padding: 10px 14px;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
        flex-shrink: 0;
    }

    .trow {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        margin-bottom: 6px;
        color: #6b7280;
    }

    .trow strong { color: #1a1a1a; font-weight: 600; }

    .trow input {
        width: 75px;
        padding: 4px 8px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        text-align: right;
        font-size: 11.5px;
        font-weight: 600;
        outline: none;
        font-family: inherit;
    }

    .trow input:focus { border-color: #ff8a3d; }

    .grand {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 8px;
        margin-top: 6px;
        border-top: 2px solid #ff8a3d;
    }

    .grand span:first-child { font-size: 13px; font-weight: 700; }
    .grand span:last-child { font-size: 20px; font-weight: 800; color: #ff8a3d; }

    /* CUSTOMER BOX */
    .customer-box {
        padding: 10px 14px 14px;
        border-top: 1px solid #f0f0f0;
        flex-shrink: 0;
        background: white;
    }

    .customer-box input,
    .customer-box select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        font-size: 11.5px;
        background: #fafafa;
        outline: none;
        font-family: inherit;
    }

    .customer-box input:focus,
    .customer-box select:focus {
        border-color: #ff8a3d;
        background: white;
    }

    /* 2-column row */
    .cb-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        margin-bottom: 6px;
    }

    .wh-select {
        border-color: #10b981 !important;
        background: #ecfdf5 !important;
        color: #065f46 !important;
        font-weight: 600;
    }

    .btn-checkout {
        width: 100%;
        padding: 11px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: white;
        border: none;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(22,163,74,0.25);
        margin-top: 4px;
        font-family: inherit;
    }

    .btn-checkout:hover:not(:disabled) {
        box-shadow: 0 5px 14px rgba(22,163,74,0.35);
        transform: translateY(-1px);
    }

    .btn-checkout:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .toast-wrap {
        position: fixed;
        top: 16px;
        right: 16px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-end;
    }

    @media (max-width: 1024px) {
        .pos-wrap { grid-template-columns: 1fr 340px; }
    }
</style>

<div class="pos-wrap">

    {{-- ============ LEFT: PRODUCTS ============ --}}
    <div class="prod-panel">
        <div class="prod-head">
            <h2>Products</h2>
            <div class="mode-tabs">
                <button type="button" id="modeSaleBtn" class="mode-tab on-sale">💵 Sale</button>
                <button type="button" id="modeReturnBtn" class="mode-tab">↩️ Return</button>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" id="searchProduct" placeholder="Search products...">
            <input type="text" id="barcodeInput" placeholder="📷 Scan" autocomplete="off">
            <button type="button" id="cameraScanBtn" title="Camera">📷</button>
        </div>

        <div class="prod-grid" id="productGrid">
            @forelse($products as $product)
                <div class="prod-card {{ $product->stock <= 0 ? 'oos' : '' }}"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-sku="{{ $product->sku }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->stock }}">
                    <div class="prod-name">{{ $product->name }}</div>
                    @if($product->sku)
                        <div class="prod-sku">{{ $product->sku }}</div>
                    @endif
                    <div class="prod-price">{{ number_format($product->price, 2) }}</div>
                    <div class="prod-stock {{ $product->stock <= 0 ? 'out' : ($product->stock <= 5 ? 'low' : 'ok') }}">
                        {{ $product->stock <= 0 ? 'Out' : $product->stock . ' in' }}
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:#9ca3af;">
                    <div style="font-size:40px;">📦</div>
                    <div>No products</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============ RIGHT: CART ============ --}}
    <div class="cart-panel" id="cartPanel">

        <div class="cart-head">
            <h2>🛒 Cart <span class="badge-count" id="cartCount">0</span></h2>
            <button type="button" id="clearCartBtn" class="clear-link">Clear</button>
        </div>

        <div class="exch-box" id="exchangePanel">
            <label>Invoice #</label>
            <input type="text" id="original_invoice" placeholder="Optional">
            <label>Reason</label>
            <input type="text" id="reason_note" placeholder="Optional">
        </div>

        <div class="cart-items" id="cartItems">
            <div class="empty-cart">
                <div class="big">🛒</div>
                <div class="msg">Cart is empty</div>
                <div class="hint">Add products to start</div>
            </div>
        </div>

        <div class="totals">
            <div class="trow">
                <span>Subtotal</span>
                <strong id="subtotal">0.00</strong>
            </div>
            <div class="trow" id="returnRow" style="display:none;">
                <span style="color:#dc2626;">Return</span>
                <strong id="returnTotal" style="color:#dc2626;">-0.00</strong>
            </div>
            <div class="trow">
                <span>Discount</span>
                <input type="number" id="discount" value="0" min="0" step="0.01">
            </div>
            <div class="trow">
                <span>Tax</span>
                <input type="number" id="tax" value="0" min="0" step="0.01">
            </div>
            <div class="grand">
                <span>Total</span>
                <span id="total">0.00</span>
            </div>
        </div>

        {{-- CUSTOMER BOX — 2 column layout --}}
        <div class="customer-box">

            {{-- Row 1: Customer name + Phone --}}
            <div class="cb-row">
                <input type="text" id="customer_name" placeholder="Customer name (optional)">
                <input type="text" id="customer_phone" placeholder="Phone (optional)">
            </div>

            {{-- Row 2: Branch + Warehouse --}}
            <div class="cb-row">
                @if($canChangeBranch)
                    <select id="branch_id">
                        <option value="">-- Branch --</option>
                        @foreach($branches as $id => $name)
                            <option value="{{ $id }}" {{ $singleBranchId == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                @else
                    @if($branches->count() > 0)
                        <input type="text" value="{{ $branches->first() }}" readonly style="background:#f3f4f6;color:#6b7280;">
                        <input type="hidden" id="branch_id" value="{{ $branches->keys()->first() }}">
                    @else
                        <input type="text" value="No branch" readonly style="background:#f3f4f6;color:#6b7280;">
                        <input type="hidden" id="branch_id" value="">
                    @endif
                @endif

                <select id="warehouse_id" class="wh-select">
                    <option value="">-- Warehouse --</option>
                    @foreach($warehouses as $w)
                        <option value="{{ $w->id }}" data-branch="{{ $w->branch_id }}" {{ $w->is_default ? 'selected' : '' }}>
                            🏭 {{ $w->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Row 3: Payment --}}
            <div style="margin-bottom: 6px;">
                <select id="payment_method">
                    <option value="cash">💵 Cash</option>
                    <option value="card">💳 Card</option>
                    <option value="credit">📝 Credit</option>
                </select>
            </div>

            <button type="button" id="checkoutBtn" class="btn-checkout">
                💰 Checkout
            </button>
        </div>
    </div>
</div>

<input type="hidden" id="sale_type" value="sale">

{{-- Camera Modal --}}
<div id="cameraModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:none;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:12px;width:100%;max-width:480px;margin:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px;border-bottom:1px solid #f0f0f0;">
            <h2 style="font-size:15px;font-weight:700;">📷 Scan</h2>
            <button type="button" onclick="closeCameraModal()" style="background:none;border:none;font-size:22px;cursor:pointer;color:#6b7280;">&times;</button>
        </div>
        <div style="padding:14px;">
            <div id="qr-reader" style="width:100%;"></div>
        </div>
    </div>
</div>

<div class="toast-wrap" id="toastContainer"></div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
var cart = [];
var currentMode = 'sale';
var checkoutUrl = "{{ route('admin.pos.checkout') }}";
var csrfToken = "{{ csrf_token() }}";
var canChangeBranch = {{ $canChangeBranch ? 'true' : 'false' }};
var productWarehouseStock = {};

function showToast(msg, type, dur) {
    type = type || 'info';
    dur = dur || 2500;
    var colors = {
        success: { bg: '#16a34a', icon: '✓' },
        error: { bg: '#dc2626', icon: '✕' },
        warning: { bg: '#d97706', icon: '⚠' },
        info: { bg: '#2563eb', icon: 'ℹ' }
    };
    var c = colors[type] || colors.info;
    var t = document.createElement('div');
    t.style.cssText = 'background:' + c.bg + ';color:white;padding:10px 16px;border-radius:8px;font-size:13px;font-weight:600;box-shadow:0 6px 20px rgba(0,0,0,0.15);display:flex;align-items:center;gap:8px;opacity:0;transform:translateX(400px);transition:all 0.3s;';
    t.innerHTML = '<span style="font-size:16px;">' + c.icon + '</span><span>' + msg + '</span>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function() { t.style.opacity = '1'; t.style.transform = 'translateX(0)'; }, 10);
    setTimeout(function() {
        t.style.opacity = '0';
        t.style.transform = 'translateX(400px)';
        setTimeout(function() { if (t.parentNode) t.parentNode.removeChild(t); }, 300);
    }, dur);
}

// MODE TOGGLE
document.getElementById('modeSaleBtn').addEventListener('click', function() {
    currentMode = 'sale';
    this.classList.add('on-sale'); this.classList.remove('on-return');
    document.getElementById('modeReturnBtn').classList.remove('on-sale', 'on-return');
    document.getElementById('exchangePanel').classList.remove('show');
    document.getElementById('sale_type').value = 'sale';
});

document.getElementById('modeReturnBtn').addEventListener('click', function() {
    currentMode = 'return';
    this.classList.add('on-return'); this.classList.remove('on-sale');
    document.getElementById('modeSaleBtn').classList.remove('on-sale', 'on-return');
    document.getElementById('exchangePanel').classList.add('show');
    document.getElementById('sale_type').value = 'exchange';
});

// CART RENDER
function renderCart() {
    var c = document.getElementById('cartItems');
    var count = cart.reduce(function(s,i){ return s + i.quantity; }, 0);
    document.getElementById('cartCount').textContent = count;

    if (cart.length === 0) {
        c.innerHTML = '<div class="empty-cart"><div class="big">🛒</div><div class="msg">Cart is empty</div><div class="hint">Add products to start</div></div>';
        updateTotals();
        return;
    }

    var html = '';
    for (var i = 0; i < cart.length; i++) {
        var it = cart[i];
        var lt = (it.price * it.quantity).toFixed(2);
        var isR = it.is_return === true;
        html += '<div class="cart-item' + (isR ? ' ret' : '') + '">';
        html += '<div class="ci-top"><div style="flex:1;">';
        if (isR) html += '<span class="return-tag">RETURN</span>';
        html += '<div class="ci-name">' + it.name + '</div>';
        html += '<div class="ci-price">' + it.price.toFixed(2) + ' ea</div>';
        html += '</div><button class="ci-remove" data-a="rm" data-i="' + i + '">&times;</button></div>';
        html += '<div class="ci-bottom">';
        html += '<div class="qty-group">';
        html += '<button class="qty-btn" data-a="dec" data-i="' + i + '">−</button>';
        html += '<span class="qty-num">' + it.quantity + '</span>';
        html += '<button class="qty-btn" data-a="inc" data-i="' + i + '">+</button>';
        html += '</div>';
        html += '<div class="ci-total' + (isR ? ' ret' : '') + '">' + (isR ? '-' : '') + lt + '</div>';
        html += '</div></div>';
    }
    c.innerHTML = html;

    c.querySelectorAll('button[data-a]').forEach(function(b) {
        b.addEventListener('click', function() {
            var a = this.dataset.a;
            var idx = parseInt(this.dataset.i);
            if (a === 'rm') removeItem(idx);
            else if (a === 'dec') changeQty(idx, -1);
            else if (a === 'inc') changeQty(idx, 1);
        });
    });

    updateTotals();
}

function changeQty(idx, d) {
    var it = cart[idx];
    if (!it) return;
    var nq = it.quantity + d;
    if (nq < 1) return;
    if (!it.is_return && nq > it.stock) return;
    it.quantity = nq;
    renderCart();
}

function removeItem(idx) {
    cart.splice(idx, 1);
    renderCart();
}

function updateTotals() {
    var ss = 0, rs = 0;
    for (var i = 0; i < cart.length; i++) {
        var l = cart[i].price * cart[i].quantity;
        if (cart[i].is_return) rs += l; else ss += l;
    }
    var d = parseFloat(document.getElementById('discount').value) || 0;
    var t = parseFloat(document.getElementById('tax').value) || 0;
    var net = ss - rs;
    document.getElementById('subtotal').textContent = net.toFixed(2);
    document.getElementById('total').textContent = (net - d + t).toFixed(2);

    var rr = document.getElementById('returnRow');
    if (rs > 0) {
        rr.style.display = 'flex';
        document.getElementById('returnTotal').textContent = '-' + rs.toFixed(2);
    } else {
        rr.style.display = 'none';
    }
}

function addToCart(id, name, price, stock) {
    var isR = (currentMode === 'return');
    var wh = document.getElementById('warehouse_id').value;
    var avail = productWarehouseStock[id] !== undefined ? productWarehouseStock[id] : stock;

    if (!wh) { showToast('Select warehouse first', 'error'); return; }
    if (!isR && avail <= 0) { showToast(name + ' out of stock', 'error'); return; }

    // Existing item — qty barhao, scroll nahi
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].product_id === id && cart[i].is_return === isR) {
            if (!isR && cart[i].quantity >= avail) { showToast('Not enough stock', 'warning'); return; }
            cart[i].quantity++;
            renderCart();
            return;
        }
    }

    // Naya item — add karo
    cart.push({ product_id: id, name: name, price: price, quantity: 1, stock: avail, is_return: isR });
    renderCart();

    // Scroll to show new item
    var panel = document.getElementById('cartPanel');
    if (panel) {
        setTimeout(function() {
            panel.scrollTop = panel.scrollHeight;
        }, 50);
    }
}

// INIT
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.prod-card').forEach(function(card) {
        card.addEventListener('click', function() {
            if (this.classList.contains('oos') && currentMode !== 'return') {
                showToast('Out of stock', 'warning');
                return;
            }
            addToCart(this.dataset.id, this.dataset.name, parseFloat(this.dataset.price), parseInt(this.dataset.stock));
        });
    });

    renderCart();

    document.getElementById('discount').addEventListener('input', updateTotals);
    document.getElementById('tax').addEventListener('input', updateTotals);

    document.getElementById('clearCartBtn').addEventListener('click', function() {
        if (cart.length === 0) return;
        if (!confirm('Clear cart?')) return;
        cart.length = 0;
        renderCart();
    });

    document.getElementById('searchProduct').addEventListener('input', function(e) {
        var q = e.target.value.toLowerCase();
        document.querySelectorAll('.prod-card').forEach(function(c) {
            var n = (c.dataset.name || '').toLowerCase();
            var s = (c.dataset.sku || '').toLowerCase();
            c.style.display = (n.indexOf(q) !== -1 || s.indexOf(q) !== -1) ? 'block' : 'none';
        });
    });
});

// BARCODE
(function() {
    var bc = document.getElementById('barcodeInput');
    if (!bc) return;
    bc.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var code = bc.value.trim();
            if (code) { processBarcode(code); bc.value = ''; }
        }
    });
    setTimeout(function() { bc.focus(); }, 500);
})();

function processBarcode(code) {
    code = String(code).trim();
    var found = null;
    document.querySelectorAll('.prod-card').forEach(function(c) {
        var sku = String(c.dataset.sku || '').trim();
        var id = String(c.dataset.id || '').trim();
        if (sku && sku.toLowerCase() === code.toLowerCase()) found = c;
        else if (id === code) found = c;
    });
    if (found) addToCart(found.dataset.id, found.dataset.name, parseFloat(found.dataset.price), parseInt(found.dataset.stock));
    else showToast('Barcode not found: ' + code, 'error');
}

// CAMERA
var scanner = null;

function openCameraModal() {
    document.getElementById('cameraModal').style.display = 'flex';
    setTimeout(startScanner, 300);
}

function closeCameraModal() {
    document.getElementById('cameraModal').style.display = 'none';
    if (scanner) { scanner.clear().catch(function(){}); scanner = null; }
}

function startScanner() {
    if (typeof Html5QrcodeScanner === 'undefined') return;
    scanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: { width: 240, height: 140 } }, false);
    scanner.render(function(t) {
        processBarcode(t);
        setTimeout(closeCameraModal, 800);
    }, function(){});
}

document.getElementById('cameraScanBtn').addEventListener('click', openCameraModal);

// CHECKOUT
document.getElementById('checkoutBtn').addEventListener('click', function() {
    if (cart.length === 0) { showToast('Cart is empty', 'warning'); return; }

    var wh = document.getElementById('warehouse_id').value;
    if (!wh) { showToast('Select warehouse', 'error'); return; }

    var br = document.getElementById('branch_id').value;
    if (!br) { showToast('Select branch', 'error'); return; }

    var payload = {
        warehouse_id: wh,
        branch_id: br,
        customer_name: document.getElementById('customer_name').value,
        customer_phone: document.getElementById('customer_phone').value,
        payment_method: document.getElementById('payment_method').value,
        discount: document.getElementById('discount').value || 0,
        tax: document.getElementById('tax').value || 0,
        type: document.getElementById('sale_type').value,
        original_invoice: document.getElementById('original_invoice') ? document.getElementById('original_invoice').value : null,
        notes: document.getElementById('reason_note') ? document.getElementById('reason_note').value : null,
        items: cart.map(function(i) { return { product_id: i.product_id, quantity: i.quantity, is_return: i.is_return }; })
    };

    var btn = this;
    btn.disabled = true;
    btn.textContent = '⏳ Processing...';

    fetch(checkoutUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(function(r){ return r.json(); })
    .then(function(d) {
        if (d.success) {
            showToast('Sale completed! ' + d.invoice_number, 'success', 2000);
            setTimeout(function() {
                if (confirm('Receipt print?\n\n' + d.invoice_number)) window.location.href = d.redirect;
                else window.location.href = "{{ route('admin.pos.index') }}";
            }, 500);
        } else {
            showToast('Error: ' + (d.message || 'Unknown'), 'error', 4000);
            btn.disabled = false;
            btn.textContent = '💰 Checkout';
        }
    })
    .catch(function() {
        showToast('Network error', 'error', 4000);
        btn.disabled = false;
        btn.textContent = '💰 Checkout';
    });
});
</script>
@endsection