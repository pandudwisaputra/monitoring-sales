<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\CommissionPayment;
use App\Services\FlipPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * Menangani webhook/callback dari pihak ketiga (seperti Flip) untuk memperbarui status pencairan dana secara real-time.
 */
class PaymentController extends Controller
{
    public function __construct(
        private FlipPaymentService $flipService,
    ) {
    }

    // =========================================================================
    // 0. INQUIRY — Validasi & daftarkan rekening di Flip
    // =========================================================================

    /**
     * Inquiry rekening untuk validasi sebelum disbursement.
     * Rekening akan terdaftar di Flip dashboard "Riwayat Cek Rekening".
     *
     * POST /api/commissions/{id}/inquiry
     */
    public function inquiryCommission(int $id): JsonResponse
    {
        $commission = Commission::with('user')->findOrFail($id);

        // Validasi status commission
        if ($commission->status === 'disbursed') {
            return response()->json([
                'message' => 'Commission sudah pernah di-disburse',
                'status'  => $commission->status,
            ], 422);
        }

        // Validasi data bank sales
        if (!$commission->user->nomor_rekening || !$commission->user->bank) {
            return response()->json([
                'message'         => 'User belum mengisi data rekening bank',
                'required_fields' => ['nomor_rekening', 'bank'],
            ], 422);
        }

        $bankCode      = $this->flipService->mapBankCode($commission->user->bank);
        $accountNumber = $commission->user->nomor_rekening;
        $inquiryKey    = "inquiry-{$commission->id}-" . time(); // unique key

        // Inquiry rekening ke Flip
        try {
            $inquiry = $this->flipService->inquiryBankAccount(
                $accountNumber,
                $bankCode,
                $inquiryKey
            );

            if (($inquiry['status'] ?? '') === 'INVALID_ACCOUNT') {
                return response()->json([
                    'message'        => 'Nomor rekening tidak valid',
                    'account_number' => $accountNumber,
                    'bank_code'      => $bankCode,
                ], 422);
            }

            // Fallback ke nama_rekening jika Flip return "Dummy Name" (sandbox)
            if (empty($inquiry['account_holder']) || $inquiry['account_holder'] === 'Dummy Name') {
                $inquiry['account_holder'] = $commission->user->nama_rekening;
            }

            return response()->json([
                'message'       => 'Rekening berhasil di-verify, siap untuk disburse',
                'commission_id' => $commission->id,
                'account_number' => $accountNumber,
                'bank_code'     => $bankCode,
                'inquiry_status' => $inquiry['status'] ?? 'PENDING',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal melakukan inquiry rekening',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // 1. DISBURSE — Bayar komisi ke rekening sales
    // =========================================================================

    /**
     * Alur lengkap pembayaran komisi:
     *   1. Validasi commission & data bank sales
     *   2. Cek saldo mencukupi
     *   3. Inquiry rekening (validasi nomor rekening)
     *   4. Kirim disbursement
     *   5. Simpan record & update status
     *
     * POST /api/commissions/{id}/disburse
     */
    public function disburse(int $id): JsonResponse
    {
        try {
            $result = $this->disbursementService->disburse($id);

            return response()->json($result);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // =========================================================================
    // 2. WEBHOOK DISBURSEMENT — Terima callback status transfer dari Flip
    // =========================================================================

    /**
     * Flip POST ke sini ketika status transfer berubah ke DONE atau CANCELLED.
     * Content-Type: application/x-www-form-urlencoded
     * Body: token, data (JSON string)
     *
     * Daftarkan URL di Flip dashboard:
     *   POST https://your-domain.com/api/webhook/flip/disbursement
     */
    public function webhookDisbursement(Request $request): JsonResponse
    {
        try {
            $payload = $this->callbackService->parseCallback($request);
        } catch (InvalidArgumentException $e) {
            $status = $e->getCode() >= 400 ? $e->getCode() : 400;

            return response()->json(['message' => $e->getMessage()], $status);
        }

        $this->callbackService->process($payload);

        return response()->json(['message' => 'OK'], 200);
    }


    // =========================================================================
    // 4. CHECK DISBURSEMENT — Polling status manual (fallback jika webhook miss)
    // =========================================================================

    /**
     * Cek status disbursement langsung dari Flip.
     * Gunakan ini sebagai fallback jika webhook tidak diterima.
     *
     * GET /api/disbursements/{disbursementId}/check
     */
    public function checkDisbursement(string $disbursementId): JsonResponse
    {
        try {
            $data    = $this->flipService->getDisbursement($disbursementId);
            $payment = CommissionPayment::where('flip_disbursement_id', $disbursementId)->first();

            if ($payment) {
                $this->callbackService->process($data);
                $payment->refresh();
            }

            return response()->json([
                'disbursement_id' => $disbursementId,
                'flip_status'     => $data['status'] ?? null,
                'app_status'      => $payment?->disbursement_status ?? null,
                'amount'          => $data['amount'] ?? null,
                'fee'             => $data['fee'] ?? null,
                'beneficiary_name' => $data['beneficiary_name'] ?? null,
                'receipt'         => $data['receipt'] ?? null,
                'time_served'     => $data['time_served'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengecek status disbursement',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // 4b. LIST BANKS — Ambil daftar bank dari Flip
    // =========================================================================

    /**
     * Ambil daftar bank Flip yang bisa dipakai di aplikasi.
     *
     * GET /api/banks
     */
    public function banks(Request $request): JsonResponse
    {
        try {
            $banks = $this->flipService->getBanks($request->boolean('refresh'));

            return response()->json([
                'data' => $banks,
                'source' => 'flip',
                'refresh' => $request->boolean('refresh'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil daftar bank dari Flip',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // 5. LIST DISBURSEMENTS — Lihat semua disbursement & status
    // =========================================================================

    /**
     * List semua disbursement dengan status terkini.
     * Berguna untuk tracking all disbursements.
     *
     * GET /api/disbursements
     */
    public function listDisbursements(Request $request): JsonResponse
    {
        $query = CommissionPayment::with(['commission.user'])
            ->orderBy('created_at', 'desc');

        // Filter by status jika ada parameter
        if ($request->has('status')) {
            $query->where('disbursement_status', $request->get('status'));
        }

        $disbursements = $query->paginate(20);

        return response()->json([
            'data' => $disbursements->items(),
            'pagination' => [
                'current_page' => $disbursements->currentPage(),
                'last_page' => $disbursements->lastPage(),
                'total' => $disbursements->total(),
                'per_page' => $disbursements->perPage(),
            ],
        ]);
    }
}