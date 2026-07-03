<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            TargetSeeder::class,
            SalesTransactionSeeder::class,
            SalesDetailSeeder::class,
            CommissionSeeder::class,
            CommissionPaymentSeeder::class,
            PaymentLogSeeder::class,
        ]);
    }
}