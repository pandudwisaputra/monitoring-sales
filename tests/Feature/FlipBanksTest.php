<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;

pest()->use(RefreshDatabase::class);

it('returns bank list from flip for authenticated users', function () {
    Cache::flush();

    $user = User::create([
        'nama' => 'User',
        'email' => 'user@example.com',
        'password' => Hash::make('password'),
        'role' => 'sales',
    ]);

    Sanctum::actingAs($user);

    Http::fake([
        '*' => Http::response([
            [
                'bank_code' => 'mandiri',
                'name' => 'Mandiri',
                'fee' => 5000,
                'queue' => 8,
                'status' => 'OPERATIONAL',
            ],
        ]),
    ]);

    $this->getJson('/api/banks')
        ->assertOk()
        ->assertJsonPath('data.0.bank_code', 'mandiri')
        ->assertJsonPath('source', 'flip');
});

it('maps bank codes dynamically from flip bank data', function () {
    Cache::flush();

    Http::fake([
        '*' => Http::response([
            [
                'bank_code' => 'mandiri',
                'name' => 'Mandiri',
                'fee' => 5000,
                'queue' => 8,
                'status' => 'OPERATIONAL',
            ],
        ]),
    ]);

    $service = app(\App\Services\FlipPaymentService::class);

    expect($service->mapBankCode('Mandiri'))->toBe('mandiri');
});