<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDisbursementController;
use App\Http\Controllers\AdminSalesController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminTargetController;
use App\Http\Controllers\SalesAuthController;
use App\Http\Controllers\SalesDashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/sales/login');
Route::redirect('/sales', '/sales/login');

// Webhook Flip (Callback)

Route::prefix('webhook/flip')->group(function () {
    Route::post('/disbursement', [AdminDisbursementController::class, 'webhookDisbursement'])
        ->name('webhook.flip.disbursement');
});

// Rute Sales (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/sales/login',  [SalesAuthController::class, 'create'])->name('sales.login');
    Route::post('/sales/login', [SalesAuthController::class, 'store'])->name('sales.login.store');
});

// Rute Sales (Authenticated)
Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::get('/sales/dashboard',   [SalesDashboardController::class, 'index'])->name('sales.dashboard');
    Route::post('/sales/logout',     [SalesAuthController::class, 'destroy'])->name('sales.logout');
    Route::post('/sales/transactions', [SalesDashboardController::class, 'storeTransaction'])->name('sales.transactions.store');
    Route::post('/sales/customers',    [SalesDashboardController::class, 'storeCustomer'])->name('sales.customers.store');
});

// Rute Admin (Guest)
Route::middleware('guest')->group(function () {
    Route::redirect('/login', '/admin/login')->name('login');
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
});

// Rute Admin (Authenticated)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export/disbursements', [AdminDashboardController::class, 'exportDisbursements'])->name('admin.export.disbursements');
    Route::get('/admin/export/sales', [AdminDashboardController::class, 'exportSales'])->name('admin.export.sales');
    Route::get('/admin/export/customers', [AdminDashboardController::class, 'exportCustomers'])->name('admin.export.customers');
    Route::get('/admin/export/targets', [AdminDashboardController::class, 'exportTargets'])->name('admin.export.targets');
    Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

    // Manajemen Sales
    Route::resource('/admin/sales', AdminSalesController::class, [
        'names' => [
            'index' => 'admin.sales.index',
            'create' => 'admin.sales.create',
            'store' => 'admin.sales.store',
            'show' => 'admin.sales.show',
            'edit' => 'admin.sales.edit',
            'update' => 'admin.sales.update',
            'destroy' => 'admin.sales.destroy',
        ],
    ]);

    // Manajemen Customer
    Route::resource('/admin/customers', App\Http\Controllers\AdminCustomerController::class, [
        'names' => [
            'index' => 'admin.customers.index',
            'create' => 'admin.customers.create',
            'store' => 'admin.customers.store',
            'show' => 'admin.customers.show',
            'edit' => 'admin.customers.edit',
            'update' => 'admin.customers.update',
            'destroy' => 'admin.customers.destroy',
        ],
    ]);

    // Manajemen Produk
    Route::resource('/admin/products', App\Http\Controllers\AdminProductController::class, [
        'names' => [
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'show' => 'admin.products.show',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ],
    ]);

    // Manajemen Disbursement
    Route::resource('/admin/disbursements', AdminDisbursementController::class, [
        'names' => [
            'index' => 'admin.disbursements.index',
            'create' => 'admin.disbursements.create',
            'store' => 'admin.disbursements.store',
            'show' => 'admin.disbursements.show',
            'edit' => 'admin.disbursements.edit',
            'update' => 'admin.disbursements.update',
            'destroy' => 'admin.disbursements.destroy',
        ],
    ]);

    // Manajemen Target
    Route::resource('/admin/targets', AdminTargetController::class, [
        'names' => [
            'index' => 'admin.targets.index',
            'create' => 'admin.targets.create',
            'store' => 'admin.targets.store',
            'show' => 'admin.targets.show',
            'edit' => 'admin.targets.edit',
            'update' => 'admin.targets.update',
            'destroy' => 'admin.targets.destroy',
        ],
    ]);

    // Generate komisi sales
    Route::post('/admin/targets/{target}/generate', [App\Http\Controllers\AdminTargetController::class, 'generateCommission'])
        ->name('admin.targets.generate');

    // Manajemen Transaksi
    Route::get('/admin/transactions/export', [AdminTransactionController::class, 'export'])->name('admin.transactions.export');
    Route::resource('/admin/transactions', AdminTransactionController::class, [
        'names' => [
            'index' => 'admin.transactions.index',
            'create' => 'admin.transactions.create',
            'store' => 'admin.transactions.store',
            'show' => 'admin.transactions.show',
            'edit' => 'admin.transactions.edit',
            'update' => 'admin.transactions.update',
            'destroy' => 'admin.transactions.destroy',
        ],
    ]);
});
