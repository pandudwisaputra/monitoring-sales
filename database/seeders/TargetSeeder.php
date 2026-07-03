<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TargetSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('targets')->insert([
            ['id' => 1, 'user_id' => 2, 'periode' => '2026-06', 'target_nominal' => 50000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 2, 'user_id' => 3, 'periode' => '2026-06', 'target_nominal' => 50000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 3, 'user_id' => 4, 'periode' => '2026-06', 'target_nominal' => 50000000.00, 'created_at' => '2026-06-17 11:57:34', 'updated_at' => '2026-06-17 11:57:34'],
            ['id' => 4, 'user_id' => 6, 'periode' => '2026-06', 'target_nominal' => 20000000.00, 'created_at' => '2026-06-18 03:22:31', 'updated_at' => '2026-06-18 03:22:31'],
            ['id' => 5, 'user_id' => 7, 'periode' => '2026-06', 'target_nominal' => 10000000.00, 'created_at' => '2026-06-25 15:12:43', 'updated_at' => '2026-06-25 15:12:43'],
            ['id' => 6, 'user_id' => 8, 'periode' => '2026-06', 'target_nominal' => 7000000.00, 'created_at' => '2026-06-25 15:45:33', 'updated_at' => '2026-06-25 15:45:33'],
            ['id' => 7, 'user_id' => 9, 'periode' => '2026-06', 'target_nominal' => 20000000.00, 'created_at' => '2026-06-29 12:44:11', 'updated_at' => '2026-06-29 12:44:11'],
            ['id' => 8, 'user_id' => 10, 'periode' => '2026-07', 'target_nominal' => 15000000.00, 'created_at' => '2026-07-01 13:18:01', 'updated_at' => '2026-07-01 13:18:01'],
            ['id' => 9, 'user_id' => 11, 'periode' => '2026-07', 'target_nominal' => 12000000.00, 'created_at' => '2026-07-01 16:08:22', 'updated_at' => '2026-07-01 16:08:22'],
            ['id' => 10, 'user_id' => 12, 'periode' => '2026-07', 'target_nominal' => 20000000.00, 'created_at' => '2026-07-01 16:31:42', 'updated_at' => '2026-07-01 16:31:42']
        ]);
    }
}
