<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesTransaction;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class SalesTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'sales')->get();
        $customers = Customer::all();

        if ($users->isEmpty() || $customers->isEmpty()) {
            return;
        }

        $currentMonth = Carbon::now()->format('Y-m');
        $startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                SalesTransaction::create([
                    'user_id' => $user->id,
                    'customer_id' => $customers->random()->id,
                    'tanggal_transaksi' => $startOfMonth->copy()->addDays(rand(0, 27)),
                    'total_harga' => rand(500000, 5000000),
                ]);
            }
        }
    }
}
