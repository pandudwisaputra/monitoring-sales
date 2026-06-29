<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'nama_produk' => 'Kulkas LG',
                'harga' => 12000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Mesin Cuci Panasonic',
                'harga' => 18000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'TV Samsung',
                'harga' => 7500000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}