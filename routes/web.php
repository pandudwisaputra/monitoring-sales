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

/*
|--------------------------------------------------------------------------
| SALES — Web Routes (session-based auth)
|--------------------------------------------------------------------------
*/

// Sales Auth (tamu saja)
Route::middleware('guest')->group(function () {
    Route::get('/sales/login',  [SalesAuthController::class, 'create'])->name('sales.login');
    Route::post('/sales/login', [SalesAuthController::class, 'store'])->name('sales.login.store');
});

// Sales Protected (harus login sebagai sales)
Route::middleware(['auth', 'role:sales'])->group(function () {
    Route::get('/sales/dashboard',   [SalesDashboardController::class, 'index'])->name('sales.dashboard');
    Route::post('/sales/logout',     [SalesAuthController::class, 'destroy'])->name('sales.logout');
    Route::post('/sales/transactions', [SalesDashboardController::class, 'storeTransaction'])->name('sales.transactions.store');
    Route::post('/sales/customers',    [SalesDashboardController::class, 'storeCustomer'])->name('sales.customers.store');
});

Route::middleware('guest')->group(function () {
    Route::redirect('/login', '/admin/login')->name('login');
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

    // Sales Management
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

    // Product Management
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

    // Disbursement Management
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

    // Target Management
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

    // Generate commission from target (single sales)
    Route::post('/admin/targets/{target}/generate', [App\Http\Controllers\AdminTargetController::class, 'generateCommission'])
        ->name('admin.targets.generate');

    // Transaction Management
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
