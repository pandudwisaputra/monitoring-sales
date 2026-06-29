<?php

namespace App\Services;

use App\Events\PaymentSuccess;
use App\Models\CommissionPayment;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class FlipDisbursementCallbackService
{
    public function __construct(private FlipPaymentService $flipService)
    {
    }

    /**
     * Verifikasi token dan parse payload callback Flip.
     *
     * @return array<string, mixed>
     *
     * @throws InvalidArgumentException
     */
    public function parseCallback(Request $request): array
    {
        $receivedToken = $request->input('token');

        if (! $this->flipService->verifyWebhookToken($receivedToken ?? '')) {
            throw new InvalidArgumentException('Unauthorized', 403);
        }

        $rawData = $request->input('data');

        if (is_array($rawData)) {
            $payload = $rawData;
        } else {
            $payload = json_decode($rawData ?? '', true);
        }

        if (! is_array($payload) || empty($payload['id'])) {
            throw new InvalidArgumentException('Invalid payload', 400);
        }

        return $payload;
    }

    /**
     * Proses callback disbursement dan update database.
     *
     * @param  array<string, mixed>  $payload
     */
    public function process(array $payload): bool
    {
        Log::info('[Flip Callback] Disbursement diterima', [
            'flip_id' => $payload['id'],
            'status'  => $payload['status'] ?? null,
        ]);

        $payment = $this->findPayment($payload);

        if (! $payment) {
            Log::warning('[Flip Callback] Payment tidak ditemukan', [
                'flip_id'          => $payload['id'],
                'idempotency_key'  => $payload['idempotency_key'] ?? null,
            ]);

            return false;
        }

        $newStatus = $this->mapFlipStatus($payload['status'] ?? 'PENDING');

        $commission = null;

        DB::transaction(function () use ($payment, $newStatus, $payload, &$commission) {
            $payment->update($this->mapPayloadToPaymentAttributes($payload, $newStatus));

            $commission = $payment->commission;

            if ($newStatus === 'completed') {
                $commission->update(['status' => 'paid']);
            } elseif ($newStatus === 'failed') {
                $commission->update(['status' => 'pending']);

                Log::warning('[Flip Callback] Transfer dibatalkan', [
                    'commission_id' => $commission->id,
                    'flip_id'       => $payload['id'],
                    'reason'        => $payload['reason'] ?? null,
                ]);
            }

            PaymentLog::create([
                'commission_id'      => $commission->id,
                'order_id'           => (string) $payload['id'],
                'transaction_status' => $newStatus,
                'payload'            => json_encode($payload),
            ]);
        });

        if ($newStatus === 'completed' && $commission) {
            try {
                event(new PaymentSuccess($commission));
            } catch (\Throwable $e) {
                Log::warning('[Flip Callback] Broadcast PaymentSuccess gagal, DB tetap tersimpan', [
                    'commission_id' => $commission->id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function findPayment(array $payload): ?CommissionPayment
    {
        $flipId = (string) $payload['id'];

        $payment = CommissionPayment::where('flip_disbursement_id', $flipId)->first();

        if ($payment) {
            return $payment;
        }

        if (! empty($payload['idempotency_key'])) {
            return CommissionPayment::where('idempotency_key', $payload['idempotency_key'])->first();
        }

        return null;
    }

    public function mapFlipStatus(string $flipStatus): string
    {
        return match (strtoupper($flipStatus)) {
            'DONE'      => 'completed',
            'CANCELLED' => 'failed',
            default     => 'pending',
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function mapPayloadToPaymentAttributes(array $payload, string $appStatus): array
    {
        $recipientName = $payload['recipient_name'] ?? null;

        if ($recipientName === '-') {
            $recipientName = null;
        }

        $attributes = [
            'flip_disbursement_id' => (string) $payload['id'],
            'disbursement_status'  => $appStatus,
            'jumlah'               => isset($payload['amount']) ? (float) $payload['amount'] : null,
            'bank_code'            => $payload['bank_code'] ?? null,
            'account_number'       => $payload['account_number'] ?? null,
            'recipient_name'       => $recipientName,
            'account_holder'       => $recipientName,
            'sender_bank'          => $payload['sender_bank'] ?? null,
            'remark'               => $payload['remark'] ?? null,
            'receipt'              => $payload['receipt'] ?? null,
            'time_served'          => $payload['time_served'] ?? null,
            'fee'                  => isset($payload['fee']) ? (int) $payload['fee'] : null,
            'beneficiary_email'    => $payload['beneficiary_email'] ?? null,
            'idempotency_key'      => $payload['idempotency_key'] ?? null,
            'direction'            => $payload['direction'] ?? null,
        ];

        if (array_key_exists('is_virtual_account', $payload)) {
            $attributes['is_virtual_account'] = filter_var(
                $payload['is_virtual_account'],
                FILTER_VALIDATE_BOOLEAN
            );
        }

        return array_filter($attributes, fn ($value) => $value !== null);
    }
}
