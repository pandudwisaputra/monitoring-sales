<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            ['id' => 1, 'nama_produk' => 'Kulkas LG', 'harga' => 12000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 2, 'nama_produk' => 'Mesin Cuci Panasonic', 'harga' => 18000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 3, 'nama_produk' => 'TV Samsung', 'harga' => 7500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 4, 'nama_produk' => 'AC Sharp 1PK', 'harga' => 5500000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34']
        ]);
    }
}
