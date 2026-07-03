<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('commissions')->insert([
            ['id' => 1, 'user_id' => 2, 'periode' => '2026-06', 'total_penjualan' => 14610148.00, 'persentase_komisi' => 0.0070, 'total_pembayaran' => 102271.04, 'status' => 'disbursed', 'created_at' => '2026-06-17 12:21:37', 'updated_at' => '2026-06-19 13:03:40'],
            ['id' => 2, 'user_id' => 3, 'periode' => '2026-06', 'total_penjualan' => 135500000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 1355000.00, 'status' => 'disbursed', 'created_at' => '2026-06-18 03:20:38', 'updated_at' => '2026-06-19 13:54:31'],
            ['id' => 3, 'user_id' => 4, 'periode' => '2026-06', 'total_penjualan' => 61500000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 615000.00, 'status' => 'paid', 'created_at' => '2026-06-19 14:14:31', 'updated_at' => '2026-06-19 14:15:02'],
            ['id' => 4, 'user_id' => 6, 'periode' => '2026-06', 'total_penjualan' => 55000000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 550000.00, 'status' => 'disbursed', 'created_at' => '2026-06-25 13:48:35', 'updated_at' => '2026-06-25 13:48:48'],
            ['id' => 5, 'user_id' => 7, 'periode' => '2026-06', 'total_penjualan' => 11000000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 110000.00, 'status' => 'paid', 'created_at' => '2026-06-25 15:13:09', 'updated_at' => '2026-06-25 15:31:58'],
            ['id' => 6, 'user_id' => 8, 'periode' => '2026-06', 'total_penjualan' => 5500000.00, 'persentase_komisi' => 0.7000, 'total_pembayaran' => 38500.00, 'status' => 'paid', 'created_at' => '2026-06-25 15:46:39', 'updated_at' => '2026-06-25 15:47:01'],
            ['id' => 7, 'user_id' => 9, 'periode' => '2026-06', 'total_penjualan' => 11000000.00, 'persentase_komisi' => 0.7000, 'total_pembayaran' => 77000.00, 'status' => 'disbursed', 'created_at' => '2026-06-29 12:46:03', 'updated_at' => '2026-06-29 12:47:21'],
            ['id' => 8, 'user_id' => 10, 'periode' => '2026-07', 'total_penjualan' => 18000000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 180000.00, 'status' => 'paid', 'created_at' => '2026-07-01 13:19:00', 'updated_at' => '2026-07-01 13:19:21'],
            ['id' => 9, 'user_id' => 11, 'periode' => '2026-07', 'total_penjualan' => 15000000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 150000.00, 'status' => 'disbursed', 'created_at' => '2026-07-01 16:09:17', 'updated_at' => '2026-07-01 16:09:44'],
            ['id' => 10, 'user_id' => 12, 'periode' => '2026-07', 'total_penjualan' => 22000000.00, 'persentase_komisi' => 1.0000, 'total_pembayaran' => 220000.00, 'status' => 'paid', 'created_at' => '2026-07-01 16:32:25', 'updated_at' => '2026-07-01 16:32:42']
        ]);
    }
}
