<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([
            ['id' => 1, 'nama_customer' => 'Budi Santoso', 'no_hp' => '081234567890', 'alamat' => 'Madiun', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 2, 'nama_customer' => 'Andi Saputra', 'no_hp' => '081298765432', 'alamat' => 'Ponorogo', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 3, 'nama_customer' => 'Siti Rahayu', 'no_hp' => '085712345678', 'alamat' => 'Ngawi', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 4, 'nama_customer' => 'Dwi Lestari', 'no_hp' => '089876543210', 'alamat' => 'Magetan', 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 7, 'nama_customer' => 'Yonooo', 'no_hp' => '0856152786817297', 'alamat' => 'Madiun', 'created_at' => '2026-06-29 04:27:29', 'updated_at' => '2026-06-29 04:27:29'],
            ['id' => 8, 'nama_customer' => 'Yanto anjay', 'no_hp' => '081324546276', 'alamat' => 'Mejayan', 'created_at' => '2026-06-29 12:44:37', 'updated_at' => '2026-06-29 12:44:37']
        ]);
    }
}
