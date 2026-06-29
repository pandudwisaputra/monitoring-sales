<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Sales 1',
            'email' => 'sales1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'nama_rekening' => 'Sales Monitoring',
            'nomor_rekening' => '9876543210',
            'bank' => 'BRI',
        ]);

        User::create([
            'nama' => 'Sales 2',
            'email' => 'sales2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'nama_rekening' => 'Sales Monitoring',
            'nomor_rekening' => '67812567642',
            'bank' => 'BRI',
        ]);

        User::create([
            'nama' => 'Sales 3',
            'email' => 'sales3@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
            'nama_rekening' => 'Sales Monitoring',
            'nomor_rekening' => '08762556123',
            'bank' => 'BRI',
        ]);
    }
}