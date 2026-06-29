<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * FlipPaymentService
 *
 * Handles semua komunikasi ke Flip for Business API.
 *
 * Base URL:
 *   Sandbox    : https://bigflip.id/big_sandbox_api
 *   Production : https://bigflip.id/api
 *
 * Auth: Basic Auth — secret_key sebagai username, password kosong
 *
 * Endpoint reference (dari docs.flip.id):
 *   GET  /v3/general/balance                    — cek saldo
 *   GET  /v2/general/banks                      — daftar bank
 *   POST /v2/disbursement/bank-account-inquiry  — validasi rekening
 *   POST /v3/disbursement                       — buat transfer
 *   GET  /v3/get-disbursement?id={id}           — cek status by flip ID
 *   GET  /v3/get-disbursement?idempotency-key={key} — cek status by idempotency key
 */
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

    // =========================================================================
    // 1. GET BALANCE
    // Docs: GET /v2/general/balance  ← v2 sesuai docs resmi
    // URL  : https://bigflip.id/big_sandbox_api/v2/general/balance
    // =========================================================================

    /**
     * Cek saldo akun Flip.
     * Panggil ini sebelum createDisbursement untuk memastikan saldo mencukupi.
     *
     * @return array ['balance' => int]
     * @throws Exception
     */
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

    // =========================================================================
    // 1b. LIST BANKS
    // Docs: GET /v2/general/banks
    // =========================================================================

    /**
     * Ambil daftar bank dari Flip.
     *
     * @param bool $refresh Paksa ambil data terbaru tanpa cache
     * @return array<int, array<string, mixed>>
     * @throws Exception
     */
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

    /**
     * Fetch raw bank data from Flip.
     *
     * @return array<int, array<string, mixed>>
     * @throws Exception
     */
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

    // =========================================================================
    // 2. BANK ACCOUNT INQUIRY
    // Docs: POST /v2/disbursement/bank-account-inquiry
    // =========================================================================

    /**
     * Validasi nomor rekening + ambil nama pemilik rekening.
     * WAJIB dipanggil sebelum createDisbursement.
     *
     * @param string $accountNumber  Nomor rekening tujuan
     * @param string $bankCode       Kode bank lowercase (bca, bni, bri, mandiri, dll)
     * @param string $inquiryKey     ID unik untuk matching async callback
     * @return array
     *   ['account_number', 'bank_code', 'account_holder', 'status' => 'SUCCESS'|'PENDING']
     * @throws Exception
     */
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

    // =========================================================================
    // 3. CREATE DISBURSEMENT
    // Docs: POST /v3/disbursement
    // Header: idempotency-key (wajib, anti double-transfer)
    // =========================================================================

    /**
     * Buat transfer ke rekening bank tujuan.
     *
     * @param array $data {
     *   @type string $account_number   Nomor rekening tujuan
     *   @type string $bank_code        Kode bank lowercase
     *   @type int    $amount           Nominal dalam rupiah
     *   @type string $remark           Keterangan transfer (maks 18 karakter)
     *   @type string $idempotency_key  ID unik per transaksi
     *   @type string $beneficiary_email Optional
     * }
     * @return array ['id', 'status' => 'PENDING', 'amount', 'fee', ...]
     * @throws Exception
     */
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

    // =========================================================================
    // 4. GET DISBURSEMENT BY ID
    // Docs: GET /v3/get-disbursement?id={flip_transaction_id}
    // =========================================================================

    /**
     * Ambil detail + status disbursement by Flip transaction ID.
     *
     * @param string $transactionId  Flip transaction ID dari response createDisbursement
     * @return array ['id', 'status', 'amount', 'fee', 'receipt', 'time_served', ...]
     * @throws Exception
     */
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

    // =========================================================================
    // 4b. GET DISBURSEMENT BY IDEMPOTENCY KEY
    // Docs: GET /v3/get-disbursement?idempotency-key={key}
    // =========================================================================

    /**
     * Ambil detail disbursement by idempotency key (merchant's transaction id).
     * Berguna untuk retry-safe check — tidak perlu simpan Flip transaction ID.
     *
     * @param string $idempotencyKey  Nilai idempotency_key yang dipakai saat createDisbursement
     * @return array
     * @throws Exception
     */
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

    // =========================================================================
    // 5. VERIFY WEBHOOK TOKEN
    // =========================================================================

    /**
     * Verifikasi token dari webhook Flip.
     * Flip POST form-urlencoded dengan field 'token' dan 'data'.
     *
     * @param string $receivedToken  Nilai 'token' dari request body
     * @return bool
     */
    public function verifyWebhookToken(string $receivedToken): bool
    {
        return hash_equals($this->validationToken, $receivedToken);
    }

    // =========================================================================
    // HELPER: Map nama bank ke kode Flip (lowercase)
    // =========================================================================

    /**
     * Konversi nama bank ke kode bank Flip (wajib lowercase).
     *
     * @param string $bankName
     * @return string
     */
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