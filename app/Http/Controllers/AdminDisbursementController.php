<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCommissionDisbursementRequest;
use App\Models\Commission;
use App\Models\CommissionPayment;
use App\Services\FlipPaymentService;
use App\Events\PaymentSuccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use InvalidArgumentException;

/**
 * Mengelola proses pencairan komisi (disbursement) ke rekening Sales dan menangani integrasi API dengan Flip.
 */
class AdminDisbursementController extends Controller
{
    public function __construct(
        private FlipPaymentService $flipService,
    ) {
    }

    public function index(Request $request): View
    {
        $query = CommissionPayment::with(['commission.user']);

        if ($request->filled('user_id')) {
            $query->whereHas('commission', function ($q) use ($request) {
                $q->where('user_id', $request->input('user_id'));
            });
        }

        if ($request->filled('status')) {
            $query->where('disbursement_status', $request->input('status'));
        }

        $payments = $query->latest()->paginate(15)->appends($request->query());
        $sales = \App\Models\User::where('role', 'sales')->orderBy('nama')->get();

        return view('admin.disbursements.index', compact('payments', 'sales'));
    }

    public function create(): View
    {
        $commissions = Commission::with('user')
            ->where('status', 'pending')
            ->whereDoesntHave('payments', function ($query) {
                $query->whereIn('disbursement_status', ['pending', 'completed']);
            })
            ->latest()
            ->get();

        return view('admin.disbursements.create', compact('commissions'));
    }

    public function store(ProcessCommissionDisbursementRequest $request): RedirectResponse
    {
        $commissionId = (int) $request->validated('commission_id');
        $commission   = Commission::with('user')->findOrFail($commissionId);

        // Validasi status
        if ($commission->status === 'paid') {
            return back()->with('error', 'Komisi ini sudah pernah dibayar.');
        }

        // Validasi data rekening
        if (! $commission->user->nomor_rekening || ! $commission->user->bank) {
            return back()->with('error', 'Sales belum mengisi data rekening bank.');
        }

        try {
            $bankCode      = $this->flipService->mapBankCode($commission->user->bank);
            $idempotencyKey = 'commission-' . $commission->id . '-' . time();

            // Cek saldo Flip
            $balance = $this->flipService->getBalance();
            if (($balance['balance'] ?? 0) < $commission->total_pembayaran) {
                return back()->with('error', 'Saldo Flip tidak mencukupi untuk melakukan disbursement.');
            }

            // Buat disbursement ke Flip
            $result = $this->flipService->createDisbursement([
                'account_number' => $commission->user->nomor_rekening,
                'bank_code'      => $bankCode,
                'amount'         => (int) $commission->total_pembayaran,
                'remark'         => 'Komisi ' . ($commission->periode ?? now()->format('Y-m')),
                'idempotency_key' => $idempotencyKey,
                'beneficiary_email' => $commission->user->email ?? null,
            ]);

            // Simpan record pembayaran
            DB::transaction(function () use ($commission, $result, $idempotencyKey) {
                CommissionPayment::create([
                    'commission_id'       => $commission->id,
                    'tanggal_bayar'       => now(),
                    'jumlah'              => $commission->total_pembayaran,
                    'flip_disbursement_id' => (string) ($result['id'] ?? null),
                    'disbursement_status' => 'pending',
                    'bank_code'           => $result['bank_code'] ?? null,
                    'account_number'      => $result['account_number'] ?? null,
                    'idempotency_key'     => $idempotencyKey,
                ]);

                $commission->update(['status' => 'disbursed']);
            });

            $flipId = $result['id'] ?? '-';

            return redirect()
                ->route('admin.disbursements.index')
                ->with('success', "Disbursement berhasil dikirim ke Flip. (Flip ID: {$flipId})");

        } catch (\Exception $e) {
            Log::error('[Disbursement] Gagal', ['message' => $e->getMessage()]);

            return back()->with('error', 'Gagal melakukan disbursement: ' . $e->getMessage());
        }
    }

    public function show(CommissionPayment $disbursement): View
    {
        $disbursement->load('commission.user');

        return view('admin.disbursements.show', compact('disbursement'));
    }

    public function edit(CommissionPayment $disbursement): View
    {
        return view('admin.disbursements.edit', compact('disbursement'));
    }

    public function update(Request $request, CommissionPayment $disbursement): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_bayar'       => ['required', 'date'],
            'jumlah'              => ['required', 'numeric', 'min:0'],
            'account_holder'      => ['nullable', 'string', 'max:255'],
            'fee'                 => ['nullable', 'numeric', 'min:0'],
            'disbursement_status' => ['required', 'in:pending,completed,failed'],
        ]);

        $disbursement->update($validated);

        return redirect()->route('admin.disbursements.index')->with('success', 'Disbursement berhasil diperbarui');
    }

    public function destroy(CommissionPayment $disbursement): RedirectResponse
    {
        $disbursement->delete();

        return redirect()->route('admin.disbursements.index')->with('success', 'Disbursement berhasil dihapus');
    }

    // Webhook callback status transfer dari Flip.
    // POST /webhook/flip/disbursement
    public function webhookDisbursement(Request $request): JsonResponse
    {
        // Verifikasi token dari Flip
        $receivedToken = $request->input('token');

        if (! $this->flipService->verifyWebhookToken($receivedToken ?? '')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Parse payload
        $rawData = $request->input('data');
        $payload = is_array($rawData) ? $rawData : json_decode($rawData ?? '', true);

        if (! is_array($payload) || empty($payload['id'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        Log::info('[Flip Webhook] Disbursement diterima', [
            'flip_id' => $payload['id'],
            'status'  => $payload['status'] ?? null,
        ]);

        // Cari payment record
        $flipId  = (string) $payload['id'];
        $payment = CommissionPayment::where('flip_disbursement_id', $flipId)->first();

        if (! $payment && ! empty($payload['idempotency_key'])) {
            $payment = CommissionPayment::where('idempotency_key', $payload['idempotency_key'])->first();
        }

        if (! $payment) {
            Log::warning('[Flip Webhook] Payment tidak ditemukan', ['flip_id' => $flipId]);
            return response()->json(['message' => 'OK'], 200);
        }

        // Map status Flip ke status aplikasi
        $newStatus = match (strtoupper($payload['status'] ?? 'PENDING')) {
            'DONE'      => 'completed',
            'CANCELLED' => 'failed',
            default     => 'pending',
        };

        // Update database
        DB::transaction(function () use ($payment, $newStatus, $payload) {
            $attributes = array_filter([
                'flip_disbursement_id' => (string) $payload['id'],
                'disbursement_status'  => $newStatus,
                'jumlah'               => isset($payload['amount']) ? (float) $payload['amount'] : null,
                'bank_code'            => $payload['bank_code'] ?? null,
                'account_number'       => $payload['account_number'] ?? null,
                'account_holder'       => $payload['recipient_name'] !== '-' ? ($payload['recipient_name'] ?? null) : null,
                'sender_bank'          => $payload['sender_bank'] ?? null,
                'remark'               => $payload['remark'] ?? null,
                'receipt'              => $payload['receipt'] ?? null,
                'time_served'          => $payload['time_served'] ?? null,
                'fee'                  => isset($payload['fee']) ? (int) $payload['fee'] : null,
                'idempotency_key'      => $payload['idempotency_key'] ?? null,
            ], fn ($v) => $v !== null);

            $payment->update($attributes);

            $commission = $payment->commission;
            if ($newStatus === 'completed') {
                $commission?->update(['status' => 'paid']);
                if ($commission) {
                    event(new PaymentSuccess($commission));
                }
            } elseif ($newStatus === 'failed') {
                $commission?->update(['status' => 'pending']);
            }
        });

        return response()->json(['message' => 'OK'], 200);
    }

    // Cek status disbursement manual langsung dari Flip.
    // GET /admin/disbursements/{disbursementId}/check
    public function checkDisbursement(string $disbursementId): JsonResponse
    {
        try {
            $data    = $this->flipService->getDisbursement($disbursementId);
            $payment = CommissionPayment::where('flip_disbursement_id', $disbursementId)->first();

            if ($payment) {
                $payment->refresh();
            }

            return response()->json([
                'disbursement_id'  => $disbursementId,
                'flip_status'      => $data['status'] ?? null,
                'app_status'       => $payment?->disbursement_status ?? null,
                'amount'           => $data['amount'] ?? null,
                'fee'              => $data['fee'] ?? null,
                'beneficiary_name' => $data['beneficiary_name'] ?? null,
                'receipt'          => $data['receipt'] ?? null,
                'time_served'      => $data['time_served'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengecek status disbursement',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
