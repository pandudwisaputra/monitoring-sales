<?php

/**
 * config/flip.php
 *
 * Konfigurasi Flip for Business API.
 *
 * Semua nilai diambil dari .env — jangan hardcode secret key di sini.
 *
 * Nilai environment yang perlu diisi di .env:
 *   FLIP_SECRET_KEY       → dari https://business.flip.id/sandbox/api-setting
 *   FLIP_VALIDATION_TOKEN → dari halaman yang sama (untuk verifikasi webhook)
 *   FLIP_ENV              → "sandbox" atau "production"
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Secret Key
    |--------------------------------------------------------------------------
    | Dipakai sebagai Basic Auth username.
    | Password dikosongkan (string kosong).
    |
    | Sandbox  : https://business.flip.id/sandbox/api-setting
    | Production: https://business.flip.id/api-setting
    */
    'secret_key' => env('FLIP_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Validation Token
    |--------------------------------------------------------------------------
    | Dipakai untuk memverifikasi bahwa webhook benar-benar dari Flip.
    | Ambil dari dashboard Flip → API Setting → Validation Token.
    | Dikirim Flip sebagai field 'token' di setiap webhook POST.
    */
    'validation_token' => env('FLIP_VALIDATION_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    | Otomatis memilih sandbox atau production berdasarkan FLIP_ENV.
    | FlipPaymentService akan menambahkan /v2 atau /v3 sesuai endpoint.
    |
    | Sandbox    : https://bigflip.id/big_sandbox_api
    | Production : https://bigflip.id/api
    */
    'base_url' => env('FLIP_ENV', 'sandbox') === 'production'
        ? 'https://bigflip.id/api'
        : 'https://bigflip.id/big_sandbox_api',

    /*
    |--------------------------------------------------------------------------
    | Environment flag
    |--------------------------------------------------------------------------
    */
    'is_production' => env('FLIP_ENV', 'sandbox') === 'production',

];