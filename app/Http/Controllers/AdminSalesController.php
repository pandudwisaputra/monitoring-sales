<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FlipPaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Mengelola data pengguna dengan peran Sales (pendaftaran, pengubahan data, pengaturan rekening) oleh Admin.
 */
class AdminSalesController extends Controller
{
    public function __construct(private FlipPaymentService $flipService)
    {
    }

    public function index(Request $request): View
    {
        $query = User::where('role', 'sales')
            ->withCount('salesTransactions', 'commissions');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sales = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.sales.index', compact('sales'));
    }

    public function create(): View
    {
        try {
            $banks = $this->flipService->getBanks();
        } catch (\Exception $e) {
            $banks = [];
        }

        return view('admin.sales.create', compact('banks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nama_rekening' => ['required', 'string', 'max:255'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'bank' => ['required', 'string', 'max:100'],
        ]);

        User::create([
            ...$validated,
            'password' => bcrypt($validated['password']),
            'role' => 'sales',
        ]);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'Sales berhasil ditambahkan');
    }

    public function show(User $sale): View
    {
        if ($sale->role !== 'sales') {
            abort(404);
        }

        return view('admin.sales.show', compact('sale'));
    }

    public function edit(User $sale): View
    {
        if ($sale->role !== 'sales') {
            abort(404);
        }

        try {
            $banks = $this->flipService->getBanks();
        } catch (\Exception $e) {
            $banks = [];
        }

        return view('admin.sales.edit', compact('sale', 'banks'));
    }

    public function update(Request $request, User $sale): RedirectResponse
    {
        if ($sale->role !== 'sales') {
            abort(404);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $sale->id],
            'nama_rekening' => ['required', 'string', 'max:255'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'bank' => ['required', 'string', 'max:100'],
        ]);

        $sale->update($validated);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'Sales berhasil diperbarui');
    }

    public function destroy(User $sale): RedirectResponse
    {
        if ($sale->role !== 'sales') {
            abort(404);
        }

        $sale->delete();

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'Sales berhasil dihapus');
    }
}
