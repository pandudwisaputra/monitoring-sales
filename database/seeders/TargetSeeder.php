<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;

class TargetSeeder extends Seeder
{
    public function run(): void
    {
        $salesUsers = User::where('role', 'sales')->get();
        $currentMonth = Carbon::now()->format('Y-m');

        foreach ($salesUsers as $user) {
            Target::create([
                'user_id' => $user->id,
                'periode' => $currentMonth,
                'target_nominal' => 50000000,
            ]);
        }
    }
}
