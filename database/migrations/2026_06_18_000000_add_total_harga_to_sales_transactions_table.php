<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('sales_transactions', 'total_harga')) {
            Schema::table('sales_transactions', function (Blueprint $table) {
                $table->decimal('total_harga', 15, 2)->default(0)->after('customer_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sales_transactions', 'total_harga')) {
            Schema::table('sales_transactions', function (Blueprint $table) {
                $table->dropColumn('total_harga');
            });
        }
    }
};
