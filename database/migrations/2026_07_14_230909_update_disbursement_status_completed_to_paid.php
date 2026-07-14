<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti 'completed' → 'paid' di tabel commission_payments
        DB::table('commission_payments')
            ->where('disbursement_status', 'completed')
            ->update(['disbursement_status' => 'paid']);

        // Ganti 'completed' → 'paid' di tabel payment_logs (transaction_status)
        DB::table('payment_logs')
            ->where('transaction_status', 'completed')
            ->update(['transaction_status' => 'paid']);
    }

    public function down(): void
    {
        // Rollback: kembalikan 'paid' → 'completed'
        DB::table('commission_payments')
            ->where('disbursement_status', 'paid')
            ->update(['disbursement_status' => 'completed']);

        DB::table('payment_logs')
            ->where('transaction_status', 'paid')
            ->update(['transaction_status' => 'completed']);
    }
};
