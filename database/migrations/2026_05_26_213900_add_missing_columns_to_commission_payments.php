<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commission_payments', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('commission_payments', 'account_holder')) {
                $table->string('account_holder')->nullable()->after('disbursement_status');
            }
            
            if (!Schema::hasColumn('commission_payments', 'fee')) {
                $table->integer('fee')->nullable()->after('account_holder');
            }
            
            if (!Schema::hasColumn('commission_payments', 'receipt')) {
                $table->text('receipt')->nullable()->after('fee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_payments', function (Blueprint $table) {
            $table->dropColumn(['account_holder', 'fee', 'receipt']);
        });
    }
};
