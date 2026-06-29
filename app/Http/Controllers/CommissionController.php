<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Commission;
use App\Models\Target;
use App\Models\User;
use App\Exports\CommissionExport;
use Maatwebsite\Excel\Facades\Excel;


class CommissionController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'periode' => 'required'
        ]);

        $periode = $request->periode;

        // ambil total penjualan per sales
        $sales = DB::table('sales_transactions')
            ->select(
                'user_id',
                DB::raw('SUM(total_harga) as total_penjualan')
            )
            ->whereRaw(
                "DATE_FORMAT(tanggal_transaksi, '%Y-%m') = ?",
                [$periode]
            )
            ->groupBy('user_id')
            ->get();

        if ($sales->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data penjualan untuk periode ini',
                'periode' => $periode
            ], 400);
        }

        $created = 0;
        foreach ($sales as $item) {

            // ambil target sales
            $target = Target::where(
                'user_id',
                $item->user_id
            )
            ->where('periode', $periode)
            ->first();

            $target_nominal = $target->target_nominal ?? 0;

            // 🔥 LOGIC KOMISI
            if ($item->total_penjualan >= $target_nominal) {

                $persen = 1;

                $komisi = $item->total_penjualan * 0.01;

            } else {

                $persen = 0.7;

                $komisi = $item->total_penjualan * 0.007;
            }

            // simpan/update komisi
            Commission::updateOrCreate(

                [
                    'user_id' => $item->user_id,
                    'periode' => $periode
                ],

                [
                    'total_penjualan' => $item->total_penjualan,
                    'persentase_komisi' => $persen,
                    'total_pembayaran' => $komisi,
                    'status' => 'pending'
                ]
            );
            $created++;
        }

        return response()->json([
            'message' => "Komisi berhasil dihitung untuk {$created} sales",
            'periode' => $periode,
            'created' => $created
        ]);
    }

public function index()
{
    $commissions = Commission::with('user')
        ->latest()
        ->get();

    return response()->json([
        'data' => $commissions
    ]);
}

public function myCommission(Request $request)
{
    $commissions = Commission::where(
        'user_id',
        auth()->id()
    )
    ->latest()
    ->get();

    return response()->json([
        'data' => $commissions
    ]);
}

public function show($id)
{
    $commission = Commission::with([
        'user',
        'payments'
    ])->findOrFail($id);

    return response()->json([
        'data' => $commission
    ]);
}


public function export()
{
    return Excel::download(
        new CommissionExport,
        'laporan-komisi.xlsx'
    );
}
}
