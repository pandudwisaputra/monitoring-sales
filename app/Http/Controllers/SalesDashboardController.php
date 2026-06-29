<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesDetail;
use App\Models\SalesTransaction;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SalesDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Transaksi milik sales ini (bulan berjalan)
        $transactions = SalesTransaction::with(['customer', 'details.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Target bulan ini
        $currentMonth = now()->format('Y-m');
        $target = Target::where('user_id', $user->id)
            ->where('periode', $currentMonth)
            ->first();

        // Total penjualan bulan ini
        $totalPenjualan = SalesTransaction::where('user_id', $user->id)
            ->whereYear('tanggal_transaksi', now()->year)
            ->whereMonth('tanggal_transaksi', now()->month)
            ->sum('total_harga');

        // Komisi milik sales ini
        $commissions = Commission::where('user_id', $user->id)
            ->latest()
            ->get();

        // Data untuk form tambah transaksi
        $customers = Customer::orderBy('nama_customer')->get();
        $products  = Product::orderBy('nama_produk')->get();

        // Ranking Penjualan Sales (Bulan Ini)
        $salesRankings = User::where('role', 'sales')
            ->withSum(['salesTransactions' => function($query) {
                $query->whereYear('tanggal_transaksi', now()->year)
                      ->whereMonth('tanggal_transaksi', now()->month);
            }], 'total_harga')
            ->get()
            ->sortByDesc('sales_transactions_sum_total_harga')
            ->values();

        $topSales = $salesRankings->take(3);
        $myRankIndex = $salesRankings->search(function($item) use ($user) {
            return $item->id === $user->id;
        });
        $myRank = $myRankIndex !== false ? $myRankIndex + 1 : '-';
        
        return view('sales.dashboard', compact(
            'user',
            'transactions',
            'target',
            'totalPenjualan',
            'commissions',
            'customers',
            'products',
            'currentMonth',
            'topSales',
            'myRank',
            'salesRankings'
        ));
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_id'          => ['required', 'exists:customers,id'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.jumlah'       => ['required', 'integer', 'min:1'],
            'items.*.harga'        => ['required', 'numeric', 'min:0'],
        ]);

        $userId       = auth()->id();
        $currentMonth = now()->format('Y-m');

        $targetExists = Target::where('user_id', $userId)
            ->where('periode', $currentMonth)
            ->exists();

        if (! $targetExists) {
            return back()
                ->withInput()
                ->with('error', 'Target bulan ini belum diatur. Anda tidak dapat menginput transaksi.');
        }

        DB::transaction(function () use ($request, $userId) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['jumlah'] * $item['harga'];
            }

            $transaction = SalesTransaction::create([
                'user_id'           => $userId,
                'customer_id'       => $request->customer_id,
                'tanggal_transaksi' => now(),
                'total_harga'       => $total,
            ]);

            foreach ($request->items as $item) {
                SalesDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'jumlah'         => $item['jumlah'],
                    'harga'          => $item['harga'],
                    'subtotal'       => $item['jumlah'] * $item['harga'],
                ]);
            }
        });

        return redirect()->route('sales.dashboard')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_customer' => ['required', 'string', 'max:255'],
            'no_hp'         => ['required', 'string', 'max:20'],
            'alamat'        => ['required', 'string'],
        ]);

        Customer::create($request->only('nama_customer', 'no_hp', 'alamat'));

        return redirect()->route('sales.dashboard')
            ->with('success_customer', 'Pelanggan berhasil ditambahkan! Silakan pilih dari dropdown.')
            ->withFragment('transaksi');
    }
}
