<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesTransactionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sales_transactions')->insert([
            ['id' => 1, 'user_id' => 2, 'customer_id' => 2, 'total_harga' => 12000000.00, 'tanggal_transaksi' => '2026-06-16', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 2, 'user_id' => 2, 'customer_id' => 3, 'total_harga' => 7500000.00, 'tanggal_transaksi' => '2026-06-06', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 3, 'user_id' => 2, 'customer_id' => 2, 'total_harga' => 5500000.00, 'tanggal_transaksi' => '2026-06-01', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 4, 'user_id' => 2, 'customer_id' => 4, 'total_harga' => 18000000.00, 'tanggal_transaksi' => '2026-06-09', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 5, 'user_id' => 2, 'customer_id' => 2, 'total_harga' => 15000000.00, 'tanggal_transaksi' => '2026-06-01', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 6, 'user_id' => 3, 'customer_id' => 3, 'total_harga' => 5500000.00, 'tanggal_transaksi' => '2026-06-01', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 7, 'user_id' => 3, 'customer_id' => 1, 'total_harga' => 18000000.00, 'tanggal_transaksi' => '2026-06-13', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 8, 'user_id' => 3, 'customer_id' => 1, 'total_harga' => 12000000.00, 'tanggal_transaksi' => '2026-06-11', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 9, 'user_id' => 3, 'customer_id' => 2, 'total_harga' => 7500000.00, 'tanggal_transaksi' => '2026-06-15', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 10, 'user_id' => 3, 'customer_id' => 1, 'total_harga' => 11000000.00, 'tanggal_transaksi' => '2026-06-14', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 11, 'user_id' => 4, 'customer_id' => 2, 'total_harga' => 7500000.00, 'tanggal_transaksi' => '2026-06-04', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 12, 'user_id' => 4, 'customer_id' => 4, 'total_harga' => 12000000.00, 'tanggal_transaksi' => '2026-06-08', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 13, 'user_id' => 4, 'customer_id' => 2, 'total_harga' => 18000000.00, 'tanggal_transaksi' => '2026-06-14', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 14, 'user_id' => 4, 'customer_id' => 1, 'total_harga' => 11000000.00, 'tanggal_transaksi' => '2026-06-18', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 15, 'user_id' => 4, 'customer_id' => 3, 'total_harga' => 13000000.00, 'tanggal_transaksi' => '2026-06-05', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 16, 'user_id' => 3, 'customer_id' => 3, 'total_harga' => 81500000.00, 'tanggal_transaksi' => '2026-06-18', 'created_at' => '2026-06-18 03:16:27', 'updated_at' => '2026-06-18 03:16:27'],
            ['id' => 17, 'user_id' => 6, 'customer_id' => 2, 'total_harga' => 55000000.00, 'tanggal_transaksi' => '2026-06-25', 'created_at' => '2026-06-25 13:48:17', 'updated_at' => '2026-06-25 13:48:17'],
            ['id' => 19, 'user_id' => 7, 'customer_id' => 1, 'total_harga' => 11000000.00, 'tanggal_transaksi' => '2026-06-25', 'created_at' => '2026-06-25 15:12:58', 'updated_at' => '2026-06-25 15:12:58'],
            ['id' => 20, 'user_id' => 8, 'customer_id' => 2, 'total_harga' => 5500000.00, 'tanggal_transaksi' => '2026-06-25', 'created_at' => '2026-06-25 15:46:31', 'updated_at' => '2026-06-25 15:46:31'],
            ['id' => 21, 'user_id' => 9, 'customer_id' => 8, 'total_harga' => 11000000.00, 'tanggal_transaksi' => '2026-06-29', 'created_at' => '2026-06-29 12:45:01', 'updated_at' => '2026-06-29 12:45:01'],
            ['id' => 22, 'user_id' => 9, 'customer_id' => 2, 'total_harga' => 35500000.00, 'tanggal_transaksi' => '2026-06-29', 'created_at' => '2026-06-29 13:12:39', 'updated_at' => '2026-06-29 13:12:39'],
            ['id' => 23, 'user_id' => 10, 'customer_id' => 7, 'total_harga' => 18000000.00, 'tanggal_transaksi' => '2026-07-01', 'created_at' => '2026-07-01 13:18:35', 'updated_at' => '2026-07-01 13:18:35'],
            ['id' => 24, 'user_id' => 11, 'customer_id' => 2, 'total_harga' => 15000000.00, 'tanggal_transaksi' => '2026-07-01', 'created_at' => '2026-07-01 16:08:58', 'updated_at' => '2026-07-01 16:08:58'],
            ['id' => 25, 'user_id' => 12, 'customer_id' => 1, 'total_harga' => 22000000.00, 'tanggal_transaksi' => '2026-07-01', 'created_at' => '2026-07-01 16:32:11', 'updated_at' => '2026-07-01 16:32:11']
        ]);
    }
}
