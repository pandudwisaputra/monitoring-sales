<?php

use App\Models\Commission;
use App\Models\CommissionPayment;
use App\Models\PaymentLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

pest()->use(RefreshDatabase::class);

function createCommissionWithPayment(array $paymentOverrides = []): CommissionPayment
{
    $user = User::create([
        'nama' => 'Yudi',
        'email' => 'yudi@example.com',
        'password' => Hash::make('password'),
        'role' => 'sales',
        'bank' => 'bca',
        'nomor_rekening' => '1234567890',
        'nama_rekening' => 'Yudi Pratama',
    ]);

    $commission = Commission::create([
        'user_id' => $user->id,
        'periode' => '2026-05',
        'total_penjualan' => 10000000,
        'persentase_komisi' => 1,
        'total_pembayaran' => 102271.04,
        'status' => 'disbursed',
    ]);

    return CommissionPayment::create(array_merge([
        'commission_id' => $commission->id,
        'tanggal_bayar' => now()->toDateString(),
        'jumlah' => 102271.04,
        'flip_disbursement_id' => '308308',
        'disbursement_status' => 'pending',
        'idempotency_key' => 'commission-1-2026-05-102271',
        'bank_code' => 'bca',
        'account_number' => '1234567890',
    ], $paymentOverrides));
}

it('updates payment and commission when flip disbursement callback is DONE', function () {
    config(['flip.validation_token' => 'test-validation-token']);
    Event::fake();

    $payment = createCommissionWithPayment();

    $payload = [
        'id' => '308308',
        'amount' => '102271',
        'status' => 'DONE',
        'bank_code' => 'bca',
        'account_number' => '1234567890',
        'recipient_name' => 'Yudi Pratama',
        'sender_bank' => 'bca',
        'remark' => 'Komisi 2026-05',
        'receipt' => 'https://flip.id/receipt/308308',
        'time_served' => '2026-06-19 20:05:00',
        'fee' => 1998,
        'beneficiary_email' => 'yudi@example.com',
        'idempotency_key' => 'commission-1-2026-05-102271',
        'direction' => 'DOMESTIC_TRANSFER',
        'is_virtual_account' => false,
    ];

    $this->post('/api/webhook/flip/disbursement', [
        'token' => 'test-validation-token',
        'data' => json_encode($payload),
    ])->assertOk();

    $payment->refresh();

    expect($payment->disbursement_status)->toBe('completed')
        ->and($payment->recipient_name)->toBe('Yudi Pratama')
        ->and($payment->receipt)->toBe('https://flip.id/receipt/308308')
        ->and($payment->fee)->toBe(1998)
        ->and($payment->commission->status)->toBe('paid');

    expect(PaymentLog::count())->toBe(1);
});

it('marks payment failed and resets commission when callback is CANCELLED', function () {
    config(['flip.validation_token' => 'test-validation-token']);

    $payment = createCommissionWithPayment();

    $this->post('/api/webhook/flip/disbursement', [
        'token' => 'test-validation-token',
        'data' => json_encode([
            'id' => '308308',
            'amount' => '102271',
            'status' => 'CANCELLED',
            'reason' => 'INACTIVE_ACCOUNT',
            'idempotency_key' => 'commission-1-2026-05-102271',
        ]),
    ])->assertOk();

    $payment->refresh();

    expect($payment->disbursement_status)->toBe('failed')
        ->and($payment->commission->status)->toBe('pending');
});

it('rejects callback with invalid token', function () {
    config(['flip.validation_token' => 'test-validation-token']);

    $this->post('/api/webhook/flip/disbursement', [
        'token' => 'wrong-token',
        'data' => json_encode(['id' => '308308', 'status' => 'DONE']),
    ])->assertForbidden();
});

it('finds payment by idempotency key when flip id is missing locally', function () {
    config(['flip.validation_token' => 'test-validation-token']);

    $payment = createCommissionWithPayment([
        'flip_disbursement_id' => null,
        'idempotency_key' => 'commission-1-2026-05-102271',
    ]);

    $this->post('/webhook/flip/disbursement', [
        'token' => 'test-validation-token',
        'data' => json_encode([
            'id' => '999999',
            'status' => 'DONE',
            'idempotency_key' => 'commission-1-2026-05-102271',
            'recipient_name' => 'Yudi Pratama',
        ]),
    ])->assertOk();

    $payment->refresh();

    expect($payment->flip_disbursement_id)->toBe('999999')
        ->and($payment->disbursement_status)->toBe('completed');
});
