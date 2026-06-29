<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCommissionDisbursementRequest;
use App\Models\Commission;
use App\Models\CommissionPayment;
use App\Services\CommissionDisbursementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class AdminDisbursementController extends Controller
{
    public function __construct(private CommissionDisbursementService $disbursementService)
    {
    }

    public function index(): View
    {
        $payments = CommissionPayment::with(['commission.user'])
            ->latest()
            ->paginate(15);

        return view('admin.disbursements.index', compact('payments'));
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
        try {
            $result = $this->disbursementService->disburse((int) $request->validated('commission_id'));

            return redirect()
                ->route('admin.disbursements.index')
                ->with('success', $result['message'] . ' (Flip ID: ' . ($result['disbursement_id'] ?? '-') . ')');
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
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
            'tanggal_bayar' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'account_holder' => ['nullable', 'string', 'max:255'],
            'fee' => ['nullable', 'numeric', 'min:0'],
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
}
