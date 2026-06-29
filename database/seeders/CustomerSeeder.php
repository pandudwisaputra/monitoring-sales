<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::insert([
            [
                'nama_customer' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'alamat' => 'Madiun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_customer' => 'Andi Saputra',
                'no_hp' => '081298765432',
                'alamat' => 'Ponorogo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}