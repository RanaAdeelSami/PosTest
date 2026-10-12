<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - POS</title>
@viteReactRefresh
@vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { height: 100%; }

        body {
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f9fa;
            color: #1a1a1a;
        }

        html body.scrollable {
            position: static !important;
            display: block !important;
            height: auto !important;
            min-height: 100vh !important;
            max-height: none !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            max-height: 100vh;
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #f0f0f0;
            transition: width 0.3s ease;
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar.collapsed { width: 70px; }

        .sidebar-header {
            padding: 20px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 20px;
            color: #1a1a1a;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
        }

        .logo-icon { font-size: 26px; flex-shrink: 0; }

        .sidebar-logo .logo-text {
            font-family: 'Georgia', serif;
            letter-spacing: -0.5px;
        }

        .sidebar-logo .logo-badge {
            font-size: 9px;
            background: #ff8a3d;
            color: white;
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .logo-badge { display: none; }

        .toggle-btn {
            background: #ff8a3d;
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            flex-shrink: 0;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(255,138,61,0.3);
        }

        .toggle-btn:hover {
            background: #e67a2f;
            transform: scale(1.05);
        }

        .sidebar-nav {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 0;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 2px;
        }

        .nav-section {
            padding: 18px 20px 8px;
            font-size: 11px;
            text-transform: capitalize;
            color: #9ca3af;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-section {
            font-size: 8px;
            text-align: center;
            padding: 10px 4px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 20px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .nav-item:hover {
            background: #fef6ee;
            color: #ff8a3d;
        }

        .nav-item.active {
            background: #fef6ee;
            color: #ff8a3d;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #ff8a3d;
        }

        .nav-icon {
            font-size: 17px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-item span:not(.nav-icon) { display: none; }
        .sidebar.collapsed .nav-item { justify-content: center; padding: 12px 0; }

        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .sidebar-footer .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 9px 14px;
            background: #fef2f2;
            border: none;
            color: #dc2626;
            border-radius: 8px;
            font-size: 13.5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.15s;
            font-family: inherit;
        }

        .sidebar-footer .logout-btn:hover { background: #fee2e2; }

        .sidebar.collapsed .sidebar-footer .logout-btn span:not(.nav-icon) { display: none; }
        .sidebar.collapsed .sidebar-footer .logout-btn { justify-content: center; padding: 10px 0; }

        /* ============ MAIN WRAPPER ============ */
        .main-wrapper {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden !important;
            display: flex;
            flex-direction: column;
        }

        html body.scrollable .main-wrapper {
            display: block !important;
            height: auto !important;
            min-height: 100vh !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .main-wrapper.expanded { margin-left: 70px; }

        /* ============ TOPBAR ============ */
        .topbar {
            background: white;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 40;
            border-bottom: 1px solid #f0f0f0;
            height: 65px;
            transition: margin-top 0.3s ease, opacity 0.3s ease;
        }

        body.topbar-hidden .topbar {
            margin-top: -65px;
            opacity: 0;
            pointer-events: none;
        }

        .topbar-toggle {
            position: absolute;
            top: 78px;
            right: -13px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #1e3a8a;
            color: white;
            border: 2px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            z-index: 45;
            box-shadow: 0 2px 8px rgba(30,58,138,0.3);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .topbar-toggle:hover {
            background: #1e40af;
            transform: scale(1.1);
        }

        .topbar-toggle.hidden { top: 8px; }

        .topbar-search {
            flex: 1;
            max-width: 380px;
            position: relative;
        }

        .topbar-search input {
            width: 100%;
            padding: 9px 40px 9px 38px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            background: #fafafa;
            transition: all 0.15s;
            font-family: inherit;
        }

        .topbar-search input:focus {
            outline: none;
            border-color: #ff8a3d;
            background: white;
            box-shadow: 0 0 0 3px rgba(255,138,61,0.1);
        }

        .topbar-search .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .topbar-search .shortcut {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #f3f4f6;
            color: #6b7280;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        .branch-selector {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            color: #374151;
            font-weight: 600;
        }

        .btn-add-new {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #ff8a3d;
            color: white;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-add-new:hover { background: #e67a2f; }

        .btn-pos {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #1e3a8a;
            color: white;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-pos:hover { background: #1e40af; }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
            position: relative;
            transition: all 0.15s;
        }

        .icon-btn:hover { background: #f3f4f6; }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            font-size: 9px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3b82f6, #1e40af);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        /* ============ CONTENT AREA ============ */
        .content-area {
            flex: 1 1 auto;
            min-height: 0;
            height: 0;
            overflow: hidden !important;
            padding: 20px 24px;
        }

        html body.scrollable .content-area {
            display: block !important;
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            flex: none !important;
        }

        /* ============ TOAST ============ */
        #toastContainer {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: flex-end;
            pointer-events: none;
        }

        .toast-msg {
            background: #16a34a;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 250px;
            max-width: 450px;
            opacity: 0;
            transform: translateX(400px);
            transition: all 0.3s ease;
            pointer-events: auto;
        }

        .toast-msg.show {
            opacity: 1;
            transform: translateX(0);
        }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .logo-text, .sidebar .logo-badge,
            .sidebar .nav-item span:not(.nav-icon) { display: none; }
            .main-wrapper { margin-left: 70px; }
            .topbar-search { display: none; }
            .topbar-toggle { display: none; }
        }
    </style>
</head>
<body class="@yield('body-class') @if(request()->routeIs('dashboard')) scrollable @endif">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <span class="logo-icon">🛍️</span>
                <span class="logo-text">Dreams</span>
                <span class="logo-badge">POS</span>
            </a>
            <button class="toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar (Ctrl+B)">‹</button>
        </div>

        <nav class="sidebar-nav">

            <div class="nav-section">Main</div>

            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span>Dashboard</span>
            </a>

            @role('super_admin')
                <a href="{{ route('admin.companies.index') }}" class="nav-item {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏢</span>
                    <span>Companies</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <span class="nav-icon">🔐</span>
                    <span>Roles</span>
                </a>
            @endrole

            <a href="{{ route('admin.branches.index') }}" class="nav-item {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                <span class="nav-icon">🏢</span>
                <span>Branches</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span>Users</span>
            </a>

            <div class="nav-section">Inventory</div>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="nav-icon">📁</span>
                <span>Category</span>
            </a>

            <a href="{{ route('admin.warehouses.index') }}" class="nav-item {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }}">
                <span class="nav-icon">🏭</span>
                <span>Warehouses</span>
            </a>

            <a href="{{ route('admin.inventory.low-stock') }}" class="nav-item {{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">
                <span class="nav-icon">⚠️</span>
                <span>Low Stocks</span>
            </a>

            <a href="{{ route('admin.suppliers.index') }}" class="nav-item {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                <span class="nav-icon">🚚</span>
                <span>Suppliers</span>
            </a>

            <a href="{{ route('admin.purchases.index') }}" class="nav-item {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
                <span class="nav-icon">📥</span>
                <span>Purchases</span>
            </a>

            <a href="{{ route('admin.supplier-returns.index') }}" class="nav-item {{ request()->routeIs('admin.supplier-returns.*') ? 'active' : '' }}">
                <span class="nav-icon">↩️</span>
                <span>Supplier Returns</span>
            </a>

            <a href="{{ route('admin.stock-transfers.index') }}" class="nav-item {{ request()->routeIs('admin.stock-transfers.*') ? 'active' : '' }}">
                <span class="nav-icon">🔄</span>
                <span>Stock Transfers</span>
            </a>

            <a href="{{ route('admin.stock-adjustments.index') }}" class="nav-item {{ request()->routeIs('admin.stock-adjustments.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                <span>Stock Adjustments</span>
            </a>

            <div class="nav-section">Sales</div>

            <a href="{{ route('admin.pos.index') }}" class="nav-item {{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span>
                <span>POS</span>
            </a>

            <a href="{{ route('admin.sales.index') }}" class="nav-item {{ request()->routeIs('admin.sales.index') ? 'active' : '' }}">
                <span class="nav-icon">🧾</span>
                <span>All Sales</span>
            </a>

            <a href="{{ route('admin.sales.returns') }}" class="nav-item {{ request()->routeIs('admin.sales.returns') ? 'active' : '' }}">
                <span class="nav-icon">↩️</span>
                <span>Sales Return</span>
            </a>

            <a href="{{ route('admin.shifts.index') }}" class="nav-item {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                <span class="nav-icon">⏰</span>
                <span>Cash Shifts</span>
            </a>

            <div class="nav-section">Reports</div>

            @can('export.profit')
                <a href="{{ route('admin.sales.profit-report') }}" class="nav-item {{ request()->routeIs('admin.sales.profit-report') ? 'active' : '' }}">
                    <span class="nav-icon">📈</span>
                    <span>Profit Report</span>
                </a>
            @endcan

            <a href="{{ route('admin.inventory.report') }}" class="nav-item {{ request()->routeIs('admin.inventory.report') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span>Stock Report</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="nav-icon">🚪</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ============ MAIN ============ --}}
    <div class="main-wrapper" id="mainWrapper">

        <button class="topbar-toggle" id="topbarToggle" onclick="toggleTopbar()" title="Toggle Topbar (Ctrl+T)">▲</button>

        <header class="topbar" id="topbar">
            <div class="topbar-search">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search">
                <span class="shortcut">⌘ K</span>
            </div>

            <div class="topbar-actions">
                @if(auth()->user()->branch)
                    <div class="branch-selector">
                        🏢 {{ auth()->user()->branch->name }} ▾
                    </div>
                @endif

                <a href="{{ route('admin.products.create') }}" class="btn-add-new">
                    ➕ Add New
                </a>

                <a href="{{ route('admin.pos.index') }}" class="btn-pos">
                    💰 POS
                </a>

                <div class="icon-btn" title="Notifications">
                    🔔
                    <span class="notification-badge">3</span>
                </div>

                <div class="icon-btn" title="Settings">⚙️</div>

                <div class="user-avatar" title="{{ auth()->user()->name }}">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    {{-- ============ TOAST CONTAINER ============ --}}
    <div id="toastContainer"></div>

    {{-- ============ CONFIRM DELETE MODAL ============ --}}
    <div id="confirmModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99999;display:none;align-items:center;justify-content:center;padding:20px;">
        <div style="background:white;border-radius:14px;width:100%;max-width:420px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,0.25);">

            <div style="padding:20px 24px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;">Confirm Delete</h2>
                <button type="button" onclick="closeConfirmModal()"
                        style="background:#dc2626;border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">
                    ×
                </button>
            </div>

            <div style="padding:24px;">
                <div style="text-align:center;">
                    <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:32px;margin:0 auto 16px;">
                        🗑️
                    </div>
                    <div style="font-size:16px;font-weight:600;color:#1a1a1a;margin-bottom:6px;">Are you sure?</div>
                    <div id="confirmMessage" style="font-size:13.5px;color:#6b7280;line-height:1.5;">
                        This action cannot be undone.
                    </div>
                </div>
            </div>

            <div style="padding:16px 24px;border-top:1px solid #f0f0f0;display:flex;justify-content:flex-end;gap:10px;background:#fafafa;">
                <button type="button" onclick="closeConfirmModal()"
                        style="padding:10px 22px;background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;">
                    Cancel
                </button>
                <button type="button" id="confirmDeleteBtn"
                        style="padding:10px 22px;background:#dc2626;color:white;border:none;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;box-shadow:0 2px 8px rgba(220,38,38,0.3);">
                    Yes, Delete
                </button>
            </div>

        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <script>window.__flashSuccess = @json(session('success'));</script>
    @endif
    @if(session('error'))
        <script>window.__flashError = @json(session('error'));</script>
    @endif
    @if(session('info'))
        <script>window.__flashInfo = @json(session('info'));</script>
    @endif

    <script>
        // ============ TOAST ============
        function showToast(message, type, duration) {
            type = type || 'info';
            duration = duration || 3000;

            var colors = {
                success: { bg: '#16a34a', icon: '✓' },
                error: { bg: '#dc2626', icon: '✕' },
                warning: { bg: '#d97706', icon: '⚠' },
                info: { bg: '#2563eb', icon: 'ℹ' }
            };

            var c = colors[type] || colors.info;
            var toast = document.createElement('div');
            toast.className = 'toast-msg';
            toast.style.background = c.bg;
            toast.innerHTML = '<span style="font-size:18px;">' + c.icon + '</span><span>' + message + '</span>';

            document.getElementById('toastContainer').appendChild(toast);

            setTimeout(function() { toast.classList.add('show'); }, 10);
            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() {
                    if (toast.parentNode) toast.parentNode.removeChild(toast);
                }, 300);
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.__flashSuccess) showToast(window.__flashSuccess, 'success');
            if (window.__flashError) showToast(window.__flashError, 'error', 4000);
            if (window.__flashInfo) showToast(window.__flashInfo, 'info');
        });

        // ============ CONFIRM DELETE ============
        var __pendingForm = null;

        function openConfirmModal(message, form) {
            __pendingForm = form;
            document.getElementById('confirmMessage').textContent = message || 'This action cannot be undone.';
            document.getElementById('confirmModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmModal() {
            __pendingForm = null;
            document.getElementById('confirmModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (__pendingForm) {
                var form = __pendingForm;
                __pendingForm = null;
                document.getElementById('confirmModal').style.display = 'none';
                document.body.style.overflow = '';
                form.submit();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('confirmModal');
                if (modal && modal.style.display === 'flex') closeConfirmModal();
            }
        });

        // Override all delete forms
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(function(form) {
                var onsubmit = form.getAttribute('onsubmit');

                if (onsubmit && onsubmit.indexOf('confirm') !== -1) {
                    var match = onsubmit.match(/confirm\(['"](.+?)['"]\)/);
                    var message = match ? match[1] : 'Are you sure you want to delete this?';

                    form.removeAttribute('onsubmit');

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        openConfirmModal(message, form);
                    });
                }
            });
        });

        // ============ SIDEBAR TOGGLE ============
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainWrapper').classList.toggle('expanded');
            localStorage.setItem('sidebar_collapsed',
                document.getElementById('sidebar').classList.contains('collapsed') ? '1' : '0');
        }

        // ============ TOPBAR TOGGLE ============
        function toggleTopbar() {
            document.body.classList.toggle('topbar-hidden');
            document.getElementById('topbarToggle').classList.toggle('hidden');
            localStorage.setItem('topbar_hidden',
                document.body.classList.contains('topbar-hidden') ? '1' : '0');
        }

        // Restore state on load
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('sidebar_collapsed') === '1') {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainWrapper').classList.add('expanded');
            }
            if (localStorage.getItem('topbar_hidden') === '1') {
                document.body.classList.add('topbar-hidden');
                document.getElementById('topbarToggle').classList.add('hidden');
            }

            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    toggleSidebar();
                }
                if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                    e.preventDefault();
                    toggleTopbar();
                }
            });
        });
    </script>

    @stack('scripts')

</body>
</html>