<?php

// Service untuk menangani integrasi API Flip (cek saldo, verifikasi rekening, dan pencairan komisi/disbursement).

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

// Integrasi API Flip
class FlipPaymentService
{
    private string $secretKey;
    private string $validationToken;
    private string $baseUrlV2;
    private string $baseUrlV3;

    public function __construct()
    {
        $this->secretKey       = config('flip.secret_key');
        $this->validationToken = config('flip.validation_token');
        $this->baseUrlV2       = config('flip.base_url') . '/v2';
        $this->baseUrlV3       = config('flip.base_url') . '/v3';
    }

    // Cek saldo akun Flip
    public function getBalance(): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->get("{$this->baseUrlV2}/general/balance"); // ← v2 sesuai docs resmi Flip

            if ($response->failed()) {
                throw new Exception("Gagal cek saldo: {$response->body()}");
            }

            return $response->json();
            // Response: { "balance": 5000000 }

        } catch (Exception $e) {
            Log::error('[Flip] getBalance error', ['message' => $e->getMessage()]);
            throw new Exception("Flip Balance Error: {$e->getMessage()}");
        }
    }

    // Ambil daftar bank Flip (dengan cache)
    public function getBanks(bool $refresh = false): array
    {
        $cacheKey = 'flip:banks';

        if (! $refresh) {
            return Cache::remember($cacheKey, now()->addHours(6), function (): array {
                return $this->fetchBanksFromFlip();
            });
        }

        $banks = $this->fetchBanksFromFlip();

        Cache::put($cacheKey, $banks, now()->addHours(6));

        return $banks;
    }

    // Ambil daftar bank dari API Flip
    private function fetchBanksFromFlip(): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->get("{$this->baseUrlV2}/general/banks");

            if ($response->failed()) {
                throw new Exception("Gagal ambil daftar bank: {$response->body()}");
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('[Flip] getBanks error', ['message' => $e->getMessage()]);

            throw new Exception("Flip Banks Error: {$e->getMessage()}");
        }
    }



    // Validasi nomor rekening dan nama pemilik
    public function inquiryBankAccount(
        string $accountNumber,
        string $bankCode,
        string $inquiryKey
    ): array {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->asForm()
                ->acceptJson()
                ->post("{$this->baseUrlV2}/disbursement/bank-account-inquiry", [
                    'account_number' => $accountNumber,
                    'bank_code'      => strtolower($bankCode),
                    'inquiry_key'    => $inquiryKey,
                ]);

            if ($response->failed()) {
                throw new Exception("Inquiry gagal: {$response->body()}");
            }

            $data = $response->json();

            Log::info('[Flip] Inquiry result', [
                'inquiry_key'    => $inquiryKey,
                'account_number' => $accountNumber,
                'bank_code'      => $bankCode,
                'status'         => $data['status'] ?? 'unknown',
                'account_holder' => $data['account_holder'] ?? null,
            ]);

            return $data;

        } catch (Exception $e) {
            Log::error('[Flip] inquiryBankAccount error', [
                'account_number' => $accountNumber,
                'bank_code'      => $bankCode,
                'message'        => $e->getMessage(),
            ]);
            throw new Exception("Flip Inquiry Error: {$e->getMessage()}");
        }
    }



    // Buat transaksi transfer / disbursement
    public function createDisbursement(array $data): array
    {
        foreach (['account_number', 'bank_code', 'amount', 'idempotency_key'] as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' wajib diisi untuk disbursement");
            }
        }

        $payload = [
            'account_number' => $data['account_number'],
            'bank_code'      => strtolower($data['bank_code']),
            'amount'         => (int) $data['amount'],
            'remark'         => substr($data['remark'] ?? 'Pembayaran Komisi', 0, 18),
        ];

        if (! empty($data['beneficiary_email'])) {
            $payload['beneficiary_email'] = $data['beneficiary_email'];
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->withHeaders([
                    'idempotency-key' => $data['idempotency_key'], // ← sesuai docs Flip
                ])
                ->asForm()
                ->acceptJson()
                ->post("{$this->baseUrlV3}/disbursement", $payload);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            $result = $response->json();

            Log::info('[Flip] Disbursement created', [
                'flip_id'         => $result['id'] ?? null,
                'idempotency_key' => $data['idempotency_key'],
                'amount'          => $data['amount'],
                'bank_code'       => $data['bank_code'],
                'status'          => $result['status'] ?? null,
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('[Flip] createDisbursement error', [
                'idempotency_key' => $data['idempotency_key'],
                'amount'          => $data['amount'],
                'message'         => $e->getMessage(),
            ]);
            throw new Exception("Flip Disbursement Error: {$e->getMessage()}");
        }
    }



    // Cek status transfer berdasarkan Flip ID
    public function getDisbursement(string $transactionId): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->get("{$this->baseUrlV3}/get-disbursement", [
                    'id' => $transactionId, // ← query param sesuai docs
                ]);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('[Flip] getDisbursement error', [
                'transaction_id' => $transactionId,
                'message'        => $e->getMessage(),
            ]);
            throw new Exception("Flip Get Disbursement Error: {$e->getMessage()}");
        }
    }



    // Cek status transfer berdasarkan Idempotency Key
    public function getDisbursementByIdempotencyKey(string $idempotencyKey): array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->acceptJson()
                ->get("{$this->baseUrlV3}/get-disbursement", [
                    'idempotency-key' => $idempotencyKey, // ← query param sesuai docs
                ]);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('[Flip] getDisbursementByIdempotencyKey error', [
                'idempotency_key' => $idempotencyKey,
                'message'         => $e->getMessage(),
            ]);
            throw new Exception("Flip Get Disbursement Error: {$e->getMessage()}");
        }
    }



    // Validasi token webhook Flip
    public function verifyWebhookToken(string $receivedToken): bool
    {
        return hash_equals($this->validationToken, $receivedToken);
    }



    // Konversi nama bank ke kode Flip
    public function mapBankCode(string $bankName): string
    {
        $normalizedBankName = strtolower(trim($bankName));

        foreach ($this->getBanks() as $bank) {
            $bankCode = strtolower(trim((string) ($bank['bank_code'] ?? '')));
            $bankLabel = strtolower(trim((string) ($bank['name'] ?? '')));

            if ($normalizedBankName === $bankCode || $normalizedBankName === $bankLabel) {
                return $bankCode;
            }
        }

        return $normalizedBankName;
    }
}