<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesDetailSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sales_details')->insert([
            ['id' => 1, 'transaction_id' => 1, 'product_id' => 1, 'jumlah' => 1, 'harga' => 12000000.00, 'subtotal' => 12000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 2, 'transaction_id' => 2, 'product_id' => 3, 'jumlah' => 1, 'harga' => 7500000.00, 'subtotal' => 7500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 3, 'transaction_id' => 3, 'product_id' => 4, 'jumlah' => 1, 'harga' => 5500000.00, 'subtotal' => 5500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 4, 'transaction_id' => 4, 'product_id' => 2, 'jumlah' => 1, 'harga' => 18000000.00, 'subtotal' => 18000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 5, 'transaction_id' => 5, 'product_id' => 3, 'jumlah' => 2, 'harga' => 7500000.00, 'subtotal' => 15000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 6, 'transaction_id' => 6, 'product_id' => 4, 'jumlah' => 1, 'harga' => 5500000.00, 'subtotal' => 5500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 7, 'transaction_id' => 7, 'product_id' => 2, 'jumlah' => 1, 'harga' => 18000000.00, 'subtotal' => 18000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 8, 'transaction_id' => 8, 'product_id' => 1, 'jumlah' => 1, 'harga' => 12000000.00, 'subtotal' => 12000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 9, 'transaction_id' => 9, 'product_id' => 3, 'jumlah' => 1, 'harga' => 7500000.00, 'subtotal' => 7500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 10, 'transaction_id' => 10, 'product_id' => 4, 'jumlah' => 2, 'harga' => 5500000.00, 'subtotal' => 11000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 11, 'transaction_id' => 11, 'product_id' => 3, 'jumlah' => 1, 'harga' => 7500000.00, 'subtotal' => 7500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 12, 'transaction_id' => 12, 'product_id' => 1, 'jumlah' => 1, 'harga' => 12000000.00, 'subtotal' => 12000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 13, 'transaction_id' => 13, 'product_id' => 2, 'jumlah' => 1, 'harga' => 18000000.00, 'subtotal' => 18000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 14, 'transaction_id' => 14, 'product_id' => 4, 'jumlah' => 2, 'harga' => 5500000.00, 'subtotal' => 11000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 15, 'transaction_id' => 15, 'product_id' => 3, 'jumlah' => 1, 'harga' => 7500000.00, 'subtotal' => 7500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 16, 'transaction_id' => 15, 'product_id' => 4, 'jumlah' => 1, 'harga' => 5500000.00, 'subtotal' => 5500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 17, 'transaction_id' => 16, 'product_id' => 4, 'jumlah' => 5, 'harga' => 5500000.00, 'subtotal' => 27500000.00, 'created_at' => '2026-06-18 03:16:27', 'updated_at' => '2026-06-18 03:16:27'],
            ['id' => 18, 'transaction_id' => 16, 'product_id' => 2, 'jumlah' => 3, 'harga' => 18000000.00, 'subtotal' => 54000000.00, 'created_at' => '2026-06-18 03:16:27', 'updated_at' => '2026-06-18 03:16:27'],
            ['id' => 19, 'transaction_id' => 17, 'product_id' => 4, 'jumlah' => 10, 'harga' => 5500000.00, 'subtotal' => 55000000.00, 'created_at' => '2026-06-25 13:48:17', 'updated_at' => '2026-06-25 13:48:17'],
            ['id' => 21, 'transaction_id' => 19, 'product_id' => 4, 'jumlah' => 2, 'harga' => 5500000.00, 'subtotal' => 11000000.00, 'created_at' => '2026-06-25 15:12:58', 'updated_at' => '2026-06-25 15:12:58'],
            ['id' => 22, 'transaction_id' => 20, 'product_id' => 4, 'jumlah' => 1, 'harga' => 5500000.00, 'subtotal' => 5500000.00, 'created_at' => '2026-06-25 15:46:31', 'updated_at' => '2026-06-25 15:46:31'],
            ['id' => 23, 'transaction_id' => 21, 'product_id' => 4, 'jumlah' => 2, 'harga' => 5500000.00, 'subtotal' => 11000000.00, 'created_at' => '2026-06-29 12:45:01', 'updated_at' => '2026-06-29 12:45:01'],
            ['id' => 24, 'transaction_id' => 22, 'product_id' => 4, 'jumlah' => 1, 'harga' => 5500000.00, 'subtotal' => 5500000.00, 'created_at' => '2026-06-29 13:12:39', 'updated_at' => '2026-06-29 13:12:39'],
            ['id' => 25, 'transaction_id' => 22, 'product_id' => 1, 'jumlah' => 1, 'harga' => 12000000.00, 'subtotal' => 12000000.00, 'created_at' => '2026-06-29 13:12:39', 'updated_at' => '2026-06-29 13:12:39'],
            ['id' => 26, 'transaction_id' => 22, 'product_id' => 2, 'jumlah' => 1, 'harga' => 18000000.00, 'subtotal' => 18000000.00, 'created_at' => '2026-06-29 13:12:39', 'updated_at' => '2026-06-29 13:12:39'],
            ['id' => 27, 'transaction_id' => 23, 'product_id' => 2, 'jumlah' => 1, 'harga' => 18000000.00, 'subtotal' => 18000000.00, 'created_at' => '2026-07-01 13:18:35', 'updated_at' => '2026-07-01 13:18:35'],
            ['id' => 28, 'transaction_id' => 24, 'product_id' => 3, 'jumlah' => 2, 'harga' => 7500000.00, 'subtotal' => 15000000.00, 'created_at' => '2026-07-01 16:08:58', 'updated_at' => '2026-07-01 16:08:58'],
            ['id' => 29, 'transaction_id' => 25, 'product_id' => 4, 'jumlah' => 4, 'harga' => 5500000.00, 'subtotal' => 22000000.00, 'created_at' => '2026-07-01 16:32:11', 'updated_at' => '2026-07-01 16:32:11']
        ]);
    }
}
