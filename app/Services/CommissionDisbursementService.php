<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\CommissionPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class CommissionDisbursementService
{
    public function __construct(private FlipPaymentService $flipService)
    {
    }

    /**
     * Proses disbursement komisi via Flip API.
     *
     * @return array{
     *   message: string,
     *   disbursement_id: string|null,
     *   amount: int|float,
     *   fee: int|float|null,
     *   bank_code: string,
     *   account_number: string,
     *   account_holder: string|null,
     *   status: string
     * }
     *
     * @throws InvalidArgumentException
     */
    public function disburse(int $commissionId): array
    {
        $commission = Commission::with('user')->findOrFail($commissionId);

        if ($commission->status !== 'pending') {
            throw new InvalidArgumentException('Commission bukan status pending');
        }

        if (! $commission->user->nomor_rekening || ! $commission->user->bank) {
            throw new InvalidArgumentException('User belum mengisi data rekening bank');
        }

        $existing = CommissionPayment::where('commission_id', $commission->id)
            ->whereIn('disbursement_status', ['pending', 'completed'])
            ->first();

        if ($existing) {
            throw new InvalidArgumentException('Commission sudah dalam proses atau sudah pernah di-disburse');
        }

        $bankCode       = $this->flipService->mapBankCode($commission->user->bank);
        $accountNumber  = $commission->user->nomor_rekening;
        $idempotencyKey = "commission-{$commission->id}-{$commission->periode}-{$commission->total_pembayaran}-" . time();
        $inquiryKey     = "inquiry-{$commission->id}";

        try {
            $balanceData = $this->flipService->getBalance();
            $balance     = $balanceData['balance'] ?? 0;
            $needed      = (int) $commission->total_pembayaran + 5000;

            if ($balance < $needed) {
                throw new InvalidArgumentException('Saldo Flip tidak mencukupi untuk melakukan disbursement');
            }
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::warning('[CommissionDisbursement] Skip balance check', ['error' => $e->getMessage()]);
        }

        try {
            $inquiry = $this->flipService->inquiryBankAccount(
                $accountNumber,
                $bankCode,
                $inquiryKey
            );

            if (($inquiry['status'] ?? '') === 'INVALID_ACCOUNT') {
                throw new InvalidArgumentException('Nomor rekening tidak valid');
            }

            if (empty($inquiry['account_holder']) || $inquiry['account_holder'] === 'Dummy Name') {
                $inquiry['account_holder'] = $commission->user->nama_rekening;
            }
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Gagal melakukan inquiry rekening: ' . $e->getMessage());
        }

        return DB::transaction(function () use ($commission, $bankCode, $accountNumber, $idempotencyKey, $inquiry) {
            try {
                $disbursement = $this->flipService->createDisbursement([
                    'account_number'    => $accountNumber,
                    'bank_code'         => $bankCode,
                    'amount'            => (int) $commission->total_pembayaran,
                    'remark'            => "Komisi {$commission->periode}",
                    'idempotency_key'   => $idempotencyKey,
                    'beneficiary_email' => $commission->user->email ?? null,
                ]);

                CommissionPayment::create([
                    'commission_id'        => $commission->id,
                    'tanggal_bayar'        => now()->toDateString(),
                    'jumlah'               => $commission->total_pembayaran,
                    'flip_disbursement_id' => $disbursement['id'] ?? null,
                    'disbursement_status'  => 'pending',
                    'account_holder'       => $inquiry['account_holder'] ?? null,
                    'bank_code'            => $bankCode,
                    'account_number'       => $accountNumber,
                    'recipient_name'       => $inquiry['account_holder'] ?? $commission->user->nama_rekening,
                    'remark'               => substr("Komisi {$commission->periode}", 0, 18),
                    'fee'                  => $disbursement['fee'] ?? null,
                    'beneficiary_email'    => $commission->user->email ?? null,
                    'idempotency_key'      => $idempotencyKey,
                ]);

                $commission->update(['status' => 'disbursed']);

                return [
                    'message'         => 'Disbursement berhasil dibuat, menunggu konfirmasi Flip',
                    'disbursement_id' => $disbursement['id'] ?? null,
                    'amount'          => $commission->total_pembayaran,
                    'fee'             => $disbursement['fee'] ?? null,
                    'bank_code'       => $bankCode,
                    'account_number'  => $accountNumber,
                    'account_holder'  => $inquiry['account_holder'] ?? null,
                    'status'          => 'pending',
                ];
            } catch (\Exception $e) {
                Log::error('[CommissionDisbursement] createDisbursement failed', [
                    'commission_id'   => $commission->id,
                    'idempotency_key' => $idempotencyKey,
                    'error'           => $e->getMessage(),
                ]);

                throw new InvalidArgumentException('Gagal membuat disbursement: ' . $e->getMessage());
            }
        });
    }
}
