<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SaleReturnController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\SupplierReturnController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\StockTransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============ SUPER ADMIN ONLY ============
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::resource('companies', CompanyController::class);

        // Role Permissions
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('roles/{role}/toggle', [RoleController::class, 'togglePermission'])->name('roles.toggle');
    });

    // ============ ADMIN / MANAGER / COMPANY HEAD ============
    Route::middleware(['role:super_admin,company_head,manager'])->prefix('admin')->name('admin.')->group(function () {

        // Master Data
        Route::resource('branches', BranchController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class);

        // ============ WAREHOUSES ============
        // Custom route PEHLE (warna {warehouse} isay pakad lega)
        Route::get('warehouses/stock-json', [WarehouseController::class, 'stockJson'])->name('warehouses.stock-json');
        Route::resource('warehouses', WarehouseController::class);

        // ============ SUPPLIERS ============
        Route::resource('suppliers', SupplierController::class);

        // ============ PURCHASES ============
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

        // ============ STOCK ADJUSTMENTS ============
        Route::resource('stock-adjustments', StockAdjustmentController::class);

        // ============ STOCK TRANSFERS ============
        Route::get('stock-transfers', [StockTransferController::class, 'index'])->name('stock-transfers.index');
        Route::get('stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
        Route::post('stock-transfers', [StockTransferController::class, 'store'])->name('stock-transfers.store');
        Route::post('stock-transfers/get-products', [StockTransferController::class, 'getWarehouseProducts'])->name('stock-transfers.get-products');
        Route::get('stock-transfers/{stockTransfer}', [StockTransferController::class, 'show'])->name('stock-transfers.show');
        Route::delete('stock-transfers/{stockTransfer}', [StockTransferController::class, 'destroy'])->name('stock-transfers.destroy');

        // ============ SUPPLIER RETURNS ============
        Route::get('supplier-returns', [SupplierReturnController::class, 'index'])->name('supplier-returns.index');
        Route::get('supplier-returns/create', [SupplierReturnController::class, 'create'])->name('supplier-returns.create');
        Route::post('supplier-returns', [SupplierReturnController::class, 'store'])->name('supplier-returns.store');
        Route::get('supplier-returns/{supplierReturn}', [SupplierReturnController::class, 'show'])->name('supplier-returns.show');
        Route::delete('supplier-returns/{supplierReturn}', [SupplierReturnController::class, 'destroy'])->name('supplier-returns.destroy');

        // ============ INVENTORY REPORTS ============
        Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
        Route::get('inventory/report', [InventoryController::class, 'stockReport'])->name('inventory.report');
    });

    // ============ SALES + POS + SHIFTS (all roles) ============
    Route::middleware(['role:super_admin,company_head,manager,salesman'])->prefix('admin')->name('admin.')->group(function () {

        // ============ SHIFTS ============
        Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::get('shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
        Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::get('shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
        Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

        // ============ POS ============
        Route::get('pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

        // ============ SALES ============
        Route::get('sales/profit-report', [SaleController::class, 'profitReport'])->name('sales.profit-report');
        Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
        Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

        // ============ SALE RETURNS ============
        Route::get('sales-returns', [SaleReturnController::class, 'index'])->name('sales.returns');
        Route::get('sales/{sale}/return', [SaleReturnController::class, 'create'])->name('sales.return.create');
        Route::post('sales/{sale}/return', [SaleReturnController::class, 'store'])->name('sales.return.store');
        Route::get('sales-returns/{return}', [SaleReturnController::class, 'show'])->name('sales.return.show');
    });

    // ============ EXPORTS (permission-based) ============
    Route::middleware(['role:super_admin,company_head,manager,salesman'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('export/sales', [ExportController::class, 'sales'])->name('export.sales');
        Route::get('export/purchases', [ExportController::class, 'purchases'])->name('export.purchases');
        Route::get('export/inventory', [ExportController::class, 'inventory'])->name('export.inventory');
        Route::get('export/profit', [ExportController::class, 'profit'])->name('export.profit');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';