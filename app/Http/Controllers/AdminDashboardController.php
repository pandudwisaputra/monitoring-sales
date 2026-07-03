<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\CommissionPayment;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Menangani tampilan ringkasan data (statistik penjualan, komisi, dll) pada halaman dashboard Admin.
 */
class AdminDashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'summaryCards' => [
                [
                    'label' => 'Total Sales',
                    'value' => User::where('role', 'sales')->count(),
                    'hint' => 'Akun sales aktif',
                ],
                [
                    'label' => 'Total Transaksi',
                    'value' => SalesTransaction::count(),
                    'hint' => 'Seluruh transaksi tersimpan',
                ],
                [
                    'label' => 'Total Komisi',
                    'value' => Commission::count(),
                    'hint' => 'Komisi per periode',
                ],
                [
                    'label' => 'Komisi Dibayar',
                    'value' => CommissionPayment::completed()->count(),
                    'hint' => 'Disbursement selesai',
                ],
                [
                    'label' => 'Total Produk',
                    'value' => Product::count(),
                    'hint' => 'Data katalog produk',
                ],
                [
                    'label' => 'Total Customer',
                    'value' => Customer::count(),
                    'hint' => 'Data customer aktif',
                ],
                [
                    'label' => 'Total Target',
                    'value' => Target::count(),
                    'hint' => 'Target penjualan',
                ],
                [
                    'label' => 'Pembayaran Pending',
                    'value' => CommissionPayment::pending()->count(),
                    'hint' => 'Menunggu konfirmasi',
                ],
            ],
            'recentSales' => SalesTransaction::with(['user', 'customer'])
                ->orderByDesc('tanggal_transaksi')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
            'recentCommissions' => Commission::with('user')
                ->latest()
                ->limit(5)
                ->get(),
            // Total penjualan per sales (ranking) for chart
            'salesRanking' => SalesTransaction::selectRaw('user_id, SUM(total_harga) as total')
                ->with('user')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->get(),
        ]);
    }
}
