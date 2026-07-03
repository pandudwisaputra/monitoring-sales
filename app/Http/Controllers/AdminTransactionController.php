<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesDetail;
use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

/**
 * Mengelola riwayat dan penginputan transaksi penjualan yang dilakukan oleh Sales, serta menghitung komisi secara otomatis.
 */
class AdminTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = SalesTransaction::with(['customer', 'user', 'details.product']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_transaksi', $request->input('tanggal'));
        }

        $transactions = $query->latest()->paginate(15)->appends($request->query());
        $sales = User::where('role', 'sales')->orderBy('nama')->get();
        $customers = Customer::orderBy('nama_customer')->get();

        return view('admin.transactions.index', compact('transactions', 'sales', 'customers'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $products = Product::orderBy('nama_produk')->get();
        $sales = User::where('role', 'sales')->orderBy('nama')->get();

        return view('admin.transactions.create', compact('customers', 'products', 'sales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
        ]);

        $currentMonth = now()->format('Y-m');
        $targetExists = \App\Models\Target::where('user_id', $validated['user_id'])
            ->where('periode', $currentMonth)
            ->exists();

        if (!$targetExists) {
            return back()->withInput()->withErrors([
                'user_id' => 'Target bulan ini untuk sales tersebut belum diatur. Tidak dapat menginput transaksi.'
            ]);
        }


        DB::transaction(function () use ($validated) {
            $totalHarga = 0;

            foreach ($validated['items'] as $item) {
                $totalHarga += $item['jumlah'] * $item['harga'];
            }

            $transaction = SalesTransaction::create([
                'user_id' => $validated['user_id'],
                'customer_id' => $validated['customer_id'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'total_harga' => $totalHarga,
            ]);

            foreach ($validated['items'] as $item) {
                SalesDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['jumlah'] * $item['harga'],
                ]);
            }
        });

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function show(SalesTransaction $transaction): View
    {
        $transaction->load(['customer', 'user', 'details.product']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit(SalesTransaction $transaction): View
    {
        $customers = Customer::orderBy('nama_customer')->get();
        $products = Product::orderBy('nama_produk')->get();
        $sales = User::where('role', 'sales')->orderBy('nama')->get();

        $transaction->load(['details.product']);

        return view('admin.transactions.edit', compact('transaction', 'customers', 'products', 'sales'));
    }

    public function update(Request $request, SalesTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'tanggal_transaksi' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.harga' => ['required', 'numeric', 'min:0'],
        ]);

        $currentMonth = now()->format('Y-m');
        $targetExists = \App\Models\Target::where('user_id', $validated['user_id'])
            ->where('periode', $currentMonth)
            ->exists();

        if (!$targetExists) {
            return back()->withInput()->withErrors([
                'user_id' => 'Target bulan ini untuk sales tersebut belum diatur. Tidak dapat mengubah/menginput transaksi.'
            ]);
        }

        DB::transaction(function () use ($transaction, $validated) {
            $totalHarga = 0;

            foreach ($validated['items'] as $item) {
                $totalHarga += $item['jumlah'] * $item['harga'];
            }

            $transaction->update([
                'user_id' => $validated['user_id'],
                'customer_id' => $validated['customer_id'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'total_harga' => $totalHarga,
            ]);

            $transaction->details()->delete();

            foreach ($validated['items'] as $item) {
                SalesDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['jumlah'] * $item['harga'],
                ]);
            }
        });

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(SalesTransaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
