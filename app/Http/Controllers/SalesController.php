<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\SalesTransaction;
use App\Models\SalesDetail;

class SalesController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'nullable|exists:customers,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.harga' => 'required|numeric|min:0'
    ]);

    // Validasi target bulan sekarang
    $currentMonth = now()->format('Y-m');
    $targetExists = \App\Models\Target::where('user_id', auth()->id())
        ->where('periode', $currentMonth)
        ->exists();

    if (!$targetExists) {
        return response()->json([
            'message' => 'Target bulan ini belum diatur. Anda tidak dapat menginput transaksi.'
        ], 403);
    }

    DB::beginTransaction();

    try {

        // hitung total
        $total = 0;

        foreach ($request->items as $item) {
            $subtotal = $item['jumlah'] * $item['harga'];
            $total += $subtotal;
        }

        // simpan header transaksi
        $transaction = SalesTransaction::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id,
            'tanggal_transaksi' => now(),
            'total_harga' => $total
        ]);

        // simpan detail
        foreach ($request->items as $item) {

            $subtotal = $item['jumlah'] * $item['harga'];

            SalesDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $subtotal
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaction
        ]);

    } catch (\Exception $e) {

        DB::rollback();

        return response()->json([
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function index()
{
    $transactions = SalesTransaction::with([
        'customer',
        'details.product',
        'user'
    ])
    ->latest()
    ->get();

    return response()->json([
        'data' => $transactions
    ]);
}

    public function show($id)
{
    $transaction = SalesTransaction::with([
        'customer',
        'details.product',
        'user'
    ])->findOrFail($id);

    return response()->json([
        'data' => $transaction
    ]);
}

public function destroy($id)
{
    $transaction = SalesTransaction::findOrFail($id);

    $transaction->delete();

    return response()->json([
        'message' => 'Transaksi berhasil dihapus'
    ]);
}
}
