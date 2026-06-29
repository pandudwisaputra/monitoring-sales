<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_transactions') && Schema::hasTable('sales_details')) {
            DB::statement(
                'UPDATE sales_transactions st
                 JOIN (
                     SELECT transaction_id, SUM(subtotal) AS total_subtotal
                     FROM sales_details
                     GROUP BY transaction_id
                 ) sd ON st.id = sd.transaction_id
                 SET st.total_harga = sd.total_subtotal
                 WHERE st.total_harga = 0'
            );
        }
    }

    public function down(): void
    {
        // No rollback for backfill.
    }
};
