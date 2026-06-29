<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    DB::statement("DROP VIEW IF EXISTS v_total_penjualan");

    DB::statement("
        CREATE VIEW v_total_penjualan AS
        SELECT
            st.user_id,
            DATE_FORMAT(st.tanggal_transaksi, '%Y-%m') AS periode,
            SUM(st.total_harga) AS total_penjualan
        FROM sales_transactions st
        GROUP BY st.user_id, DATE_FORMAT(st.tanggal_transaksi, '%Y-%m')
    ");
}

    public function down(): void
{
    DB::statement("DROP VIEW IF EXISTS v_total_penjualan");
}
};