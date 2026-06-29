<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Commission;
use App\Models\SalesTransaction;

class DashboardController extends Controller
{
    public function admin()
    {
        return response()->json([

            'total_sales_transaksi' =>
                SalesTransaction::count(),

            'total_produk' =>
                Product::count(),

            'total_sales' =>
                User::where('role', 'sales')
                    ->count(),

            'total_komisi' =>
                Commission::sum('total_komisi')
        ]);
    }
}