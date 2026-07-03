<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = bcrypt('password');

        DB::table('users')->insert([
            ['id' => 1, 'nama' => 'Admin', 'email' => 'admin@gmail.com', 'password' => $password, 'role' => 'admin', 'nama_rekening' => null, 'nomor_rekening' => null, 'bank' => null, 'remember_token' => 'Xwc0yiENtUDaDlurRIiNvDPIUvqifZhcMe6gVwqD0rgdYQyj9RXUiSnEY3ko', 'created_at' => '2026-06-17 11:57:33', 'updated_at' => '2026-06-17 11:57:33'],
            ['id' => 2, 'nama' => 'Yudi', 'email' => 'yudi@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'Yudi Pratama', 'nomor_rekening' => '9876543210', 'bank' => 'BRI', 'remember_token' => null, 'created_at' => '2026-06-17 11:57:33', 'updated_at' => '2026-06-17 12:07:32'],
            ['id' => 3, 'nama' => 'Huda', 'email' => 'huda@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'Huda Kurniawan', 'nomor_rekening' => '67812567642', 'bank' => 'BRI', 'remember_token' => null, 'created_at' => '2026-06-17 11:57:33', 'updated_at' => '2026-06-17 12:07:45'],
            ['id' => 4, 'nama' => 'Rizki', 'email' => 'rizki@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'Rizki Aditya', 'nomor_rekening' => '08762556123', 'bank' => 'BRI', 'remember_token' => null, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 12:08:07'],
            ['id' => 6, 'nama' => 'Pandu', 'email' => 'pandu@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'Pandu', 'nomor_rekening' => '09989128', 'bank' => 'BSI (Bank Syariah Indonesia)', 'remember_token' => null, 'created_at' => '2026-06-18 03:22:17', 'updated_at' => '2026-06-18 03:22:17'],
            ['id' => 7, 'nama' => 'Suroso', 'email' => 'suroso@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'Suroso', 'nomor_rekening' => '81929791297', 'bank' => 'Bank Kalteng', 'remember_token' => null, 'created_at' => '2026-06-25 15:01:25', 'updated_at' => '2026-06-25 15:01:25'],
            ['id' => 8, 'nama' => 'loko', 'email' => 'loko@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'loko', 'nomor_rekening' => '65777756', 'bank' => 'Bank Permata', 'remember_token' => null, 'created_at' => '2026-06-25 15:45:14', 'updated_at' => '2026-06-25 15:45:14'],
            ['id' => 9, 'nama' => 'Ahmad', 'email' => 'ahmad@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'ahmad', 'nomor_rekening' => '1029109209', 'bank' => 'Panin Bank', 'remember_token' => 'AQuuWu338c2goWn0HexEJ3uEOVXqsyBZEO5P0Youq37enNiEBj7GxPi5tYBM', 'created_at' => '2026-06-29 12:43:15', 'updated_at' => '2026-06-29 12:43:15'],
            ['id' => 10, 'nama' => 'roni', 'email' => 'roni@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'roni', 'nomor_rekening' => '8918291728', 'bank' => 'OCBC NISP', 'remember_token' => null, 'created_at' => '2026-07-01 13:17:34', 'updated_at' => '2026-07-01 13:17:34'],
            ['id' => 11, 'nama' => 'yare', 'email' => 'yare@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'yare', 'nomor_rekening' => '1212132323', 'bank' => 'ANZ Indonesia', 'remember_token' => null, 'created_at' => '2026-07-01 16:07:50', 'updated_at' => '2026-07-01 16:07:50'],
            ['id' => 12, 'nama' => 'yuli', 'email' => 'yuli@gmail.com', 'password' => $password, 'role' => 'sales', 'nama_rekening' => 'yuli', 'nomor_rekening' => '91829792', 'bank' => 'Bank of Tokyo Mitsubishi UFJ', 'remember_token' => null, 'created_at' => '2026-07-01 16:25:33', 'updated_at' => '2026-07-01 16:25:33']
        ]);
    }
}
