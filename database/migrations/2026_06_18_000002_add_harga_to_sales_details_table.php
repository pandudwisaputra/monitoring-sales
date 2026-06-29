<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_details') && !Schema::hasColumn('sales_details', 'harga')) {
            Schema::table('sales_details', function (Blueprint $table) {
                $table->decimal('harga', 15, 2)->default(0)->after('jumlah');
            });

            DB::statement(
                'UPDATE sales_details SET harga = CASE WHEN jumlah > 0 THEN subtotal / jumlah ELSE 0 END WHERE harga = 0'
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sales_details') && Schema::hasColumn('sales_details', 'harga')) {
            Schema::table('sales_details', function (Blueprint $table) {
                $table->dropColumn('harga');
            });
        }
    }
};
