<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCommissionController extends Controller
{
    public function index(): View
    {
        $commissions = Commission::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.commissions.index', compact('commissions'));
    }

    public function create(): View
    {
        $sales = User::where('role', 'sales')
            ->orderBy('nama')
            ->get();

        return view('admin.commissions.create', compact('sales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode' => ['required', 'date_format:Y-m'],
            'total_penjualan' => ['required', 'numeric', 'min:0'],
        ]);

        // business rule: persentase komisi otomatis 1% dan total_pembayaran dihitung
        $persentase = 1.0; // 1%
        $totalPembayaran = ($validated['total_penjualan'] * ($persentase / 100));

        Commission::create([
            'user_id' => $validated['user_id'],
            'periode' => $validated['periode'],
            'total_penjualan' => $validated['total_penjualan'],
            'persentase_komisi' => $persentase,
            'total_pembayaran' => $totalPembayaran,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Komisi berhasil ditambahkan');
    }

    public function show(Commission $commission): View
    {
        $commission->load(['user', 'payments']);

        return view('admin.commissions.show', compact('commission'));
    }

    public function edit(Commission $commission): View
    {
        $sales = User::where('role', 'sales')
            ->orderBy('nama')
            ->get();

        return view('admin.commissions.edit', compact('commission', 'sales'));
    }

    public function update(Request $request, Commission $commission): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode' => ['required', 'date_format:Y-m'],
            'total_penjualan' => ['required', 'numeric', 'min:0'],
        ]);

        // persentase selalu 1% dan total_pembayaran otomatis dihitung; status tidak diubah dari sebelumnya
        $persentase = 1.0;
        $totalPembayaran = ($validated['total_penjualan'] * ($persentase / 100));

        $commission->update([
            'user_id' => $validated['user_id'],
            'periode' => $validated['periode'],
            'total_penjualan' => $validated['total_penjualan'],
            'persentase_komisi' => $persentase,
            'total_pembayaran' => $totalPembayaran,
        ]);

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Komisi berhasil diperbarui');
    }

    public function destroy(Commission $commission): RedirectResponse
    {
        $commission->delete();

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', 'Komisi berhasil dihapus');
    }
}
