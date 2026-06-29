<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\SalesTransaction;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminTargetController extends Controller
{
    public function index(): View
    {
        $targets = Target::with('user')->latest()->paginate(15);

        return view('admin.targets.index', compact('targets'));
    }

    public function create(): View
    {
        $sales = User::where('role', 'sales')->orderBy('nama')->get();

        return view('admin.targets.create', compact('sales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode' => ['required', 'date_format:Y-m'],
            'target_nominal' => ['required', 'numeric', 'min:0'],
        ]);

        Target::create($validated);

        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil ditambahkan');
    }

    public function show(Target $target): View
    {
        $target->load('user');

        return view('admin.targets.show', compact('target'));
    }

    public function edit(Target $target): View
    {
        $sales = User::where('role', 'sales')->orderBy('nama')->get();

        return view('admin.targets.edit', compact('target', 'sales'));
    }

    public function update(Request $request, Target $target): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'periode' => ['required', 'date_format:Y-m'],
            'target_nominal' => ['required', 'numeric', 'min:0'],
        ]);

        $target->update($validated);

        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil diperbarui');
    }

    public function generateCommission(Target $target): RedirectResponse
    {
        // hitung total penjualan untuk sales pada periode ini
        $total = SalesTransaction::where('user_id', $target->user_id)
            ->whereRaw("DATE_FORMAT(tanggal_transaksi, '%Y-%m') = ?", [$target->periode])
            ->sum('total_harga');

        if ((float) $total <= 0) {
            return redirect()->route('admin.targets.index')->with('error', 'Tidak ada penjualan untuk sales ini pada periode tersebut.');
        }

        $target_nominal = $target->target_nominal ?? 0;

        if ($total >= $target_nominal) {
            $persen = 1;
            $komisi = $total * 0.01;
        } else {
            $persen = 0.7;
            $komisi = $total * 0.007;
        }

        Commission::updateOrCreate(
            [
                'user_id' => $target->user_id,
                'periode' => $target->periode,
            ],
            [
                'total_penjualan' => $total,
                'persentase_komisi' => $persen,
                'total_pembayaran' => $komisi,
                'status' => 'pending',
            ]
        );

        return redirect()->route('admin.targets.show', $target->id)->with('success', 'Komisi berhasil digenerate untuk sales ini.');
    }

    public function destroy(Target $target): RedirectResponse
    {
        $target->delete();

        return redirect()->route('admin.targets.index')->with('success', 'Target berhasil dihapus');
    }
}
