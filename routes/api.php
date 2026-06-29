<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TargetController;

/*
|--------------------------------------------------------------------------
| AUTH — Public routes (tidak butuh token)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| WEBHOOK FLIP — Tanpa auth middleware
|--------------------------------------------------------------------------
| Flip tidak mengirim Authorization header.
| Verifikasi keamanan dilakukan via 'token' di dalam request body.
|
| ⚠️  Wajib exclude dari CSRF:
|
|   Laravel 11 — bootstrap/app.php:
|     ->withMiddleware(function (Middleware $middleware) {
|         $middleware->validateCsrfTokens(except: [
|             'api/webhook/flip/*',
|         ]);
|     })
|
|   Laravel 10 — App\Http\Middleware\VerifyCsrfToken.php:
|     protected $except = ['api/webhook/flip/*'];
|
|--------------------------------------------------------------------------
| Daftarkan URL ini di Flip dashboard:
|   https://business.flip.id/sandbox/api-setting
|
|   Disbursement callback :
|     https://your-domain.com/api/webhook/flip/disbursement
|     https://your-domain.com/webhook/flip/disbursement
|
| Testing lokal: npx localtunnel --port 8000  atau  ./ngrok http 8000
|--------------------------------------------------------------------------
*/

Route::prefix('webhook/flip')->group(function () {

    // Callback status transfer: PENDING → DONE / CANCELLED
    Route::post('/disbursement', [PaymentController::class, 'webhookDisbursement']);

});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES — Butuh Bearer Token (Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |----------------------------------------------------------------------
    | PROFILE & AUTH
    |----------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    /*
    |----------------------------------------------------------------------
    | PRODUCTS — Read: semua role | Write: admin only (lihat bawah)
    |----------------------------------------------------------------------
    */

    Route::get('/products',      [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    /*
    |----------------------------------------------------------------------
    | CUSTOMERS — Read: semua role | Write: admin only (lihat bawah)
    |----------------------------------------------------------------------
    */

    Route::get('/customers',      [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);

    /*
    |----------------------------------------------------------------------
    | DISBURSEMENT — Polling status (semua role yang login bisa cek)
    |----------------------------------------------------------------------
    */

    // List all disbursements
    Route::get('/disbursements', [PaymentController::class, 'listDisbursements']);
    
    // Fallback polling jika webhook dari Flip tidak diterima
    Route::get('/disbursements/{disbursementId}/check', [PaymentController::class, 'checkDisbursement']);

    // Daftar bank dari Flip untuk kebutuhan form / mapping bank secara dinamis
    Route::get('/banks', [PaymentController::class, 'banks']);

    /*
    |======================================================================
    | ADMIN ONLY
    |======================================================================
    */

    Route::middleware('role:admin')->group(function () {

        /*
        |------------------------------------------------------------------
        | DASHBOARD ADMIN
        |------------------------------------------------------------------
        */

        Route::get('/dashboard/admin', [DashboardController::class, 'admin']);

        /*
        |------------------------------------------------------------------
        | SALES REGISTRATION
        |------------------------------------------------------------------
        */

        Route::post('/sales/register', [AuthController::class, 'register']);

        /*
        |------------------------------------------------------------------
        | TRANSACTIONS
        |------------------------------------------------------------------
        */

        Route::get('/transactions',      [SalesController::class, 'index']);
        Route::get('/transactions/{id}', [SalesController::class, 'show']);
        Route::delete('/transactions/{id}', [SalesController::class, 'destroy']);

        /*
        |------------------------------------------------------------------
        | COMMISSIONS
        |
        | ⚠️  URUTAN PENTING — route statis (/export, /generate) HARUS
        |     didaftarkan SEBELUM route dengan parameter dinamis (/{id}).
        |     Laravel membaca dari atas ke bawah; jika /{id} lebih dulu,
        |     string "export" atau "generate" akan dianggap sebagai {id}.
        |------------------------------------------------------------------
        */

        // Statis — daftarkan lebih dulu
        Route::get('/commissions',           [CommissionController::class, 'index']);
        Route::get('/commissions/export',    [CommissionController::class, 'export']);   // ← sebelum /{id}
        Route::post('/commissions/generate', [CommissionController::class, 'generate']); // ← sebelum /{id}

        // Dinamis — daftarkan setelah semua route statis
        Route::get('/commissions/{id}',              [CommissionController::class, 'show']);
        Route::post('/commissions/{id}/inquiry',     [PaymentController::class,    'inquiryCommission']);
        Route::post('/commissions/{id}/disburse',    [PaymentController::class,    'disburse']);

        /*
        |------------------------------------------------------------------
        | PRODUCTS — Write
        |------------------------------------------------------------------
        */

        Route::post('/products',      [ProductController::class, 'store']);
        Route::put('/products/{id}',  [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        /*
        |------------------------------------------------------------------
        | CUSTOMERS — Write
        |------------------------------------------------------------------
        */

        Route::post('/customers',       [CustomerController::class, 'store']);
        Route::put('/customers/{id}',   [CustomerController::class, 'update']);
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

        /*
        |------------------------------------------------------------------
        | TARGETS
        |------------------------------------------------------------------
        */

        Route::get('/targets',       [TargetController::class, 'index']);
        Route::get('/targets/{id}',  [TargetController::class, 'show']);
        Route::post('/targets',      [TargetController::class, 'store']);
        Route::put('/targets/{id}',  [TargetController::class, 'update']);
        Route::delete('/targets/{id}', [TargetController::class, 'destroy']);

    }); // end role:admin

    /*
    |======================================================================
    | SALES ONLY
    |======================================================================
    */

    Route::middleware('role:sales')->group(function () {

        // Input transaksi baru
        Route::post('/transactions', [SalesController::class, 'store']);

        // Lihat komisi milik sendiri
        Route::get('/my-commissions', [CommissionController::class, 'myCommission']);

        // Sales boleh tambah pelanggan baru
        Route::post('/customers', [CustomerController::class, 'store']);

    }); // end role:sales

}); // end auth:sanctum