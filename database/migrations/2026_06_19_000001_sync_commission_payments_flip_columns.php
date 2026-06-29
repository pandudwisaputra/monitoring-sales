<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('commission_payments', 'flip_disbursement_id')) {
                $table->string('flip_disbursement_id')->nullable()->after('jumlah');
            }

            if (! Schema::hasColumn('commission_payments', 'bank_code')) {
                $table->string('bank_code')->nullable()->after('account_holder');
            }

            if (! Schema::hasColumn('commission_payments', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_code');
            }

            if (! Schema::hasColumn('commission_payments', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('account_number');
            }

            if (! Schema::hasColumn('commission_payments', 'sender_bank')) {
                $table->string('sender_bank')->nullable()->after('recipient_name');
            }

            if (! Schema::hasColumn('commission_payments', 'remark')) {
                $table->string('remark')->nullable()->after('sender_bank');
            }

            if (! Schema::hasColumn('commission_payments', 'time_served')) {
                $table->string('time_served')->nullable()->after('receipt');
            }

            if (! Schema::hasColumn('commission_payments', 'beneficiary_email')) {
                $table->string('beneficiary_email')->nullable()->after('fee');
            }

            if (! Schema::hasColumn('commission_payments', 'idempotency_key')) {
                $table->string('idempotency_key')->nullable()->after('beneficiary_email');
            }

            if (! Schema::hasColumn('commission_payments', 'direction')) {
                $table->string('direction')->nullable()->after('idempotency_key');
            }

            if (! Schema::hasColumn('commission_payments', 'is_virtual_account')) {
                $table->boolean('is_virtual_account')->default(false)->after('direction');
            }
        });

        if (
            Schema::hasColumn('commission_payments', 'disbursement_id')
            && Schema::hasColumn('commission_payments', 'flip_disbursement_id')
        ) {
            DB::table('commission_payments')
                ->whereNull('flip_disbursement_id')
                ->whereNotNull('disbursement_id')
                ->update(['flip_disbursement_id' => DB::raw('disbursement_id')]);
        }
    }

    public function down(): void
    {
        Schema::table('commission_payments', function (Blueprint $table) {
            $columns = [
                'flip_disbursement_id',
                'bank_code',
                'account_number',
                'recipient_name',
                'sender_bank',
                'remark',
                'time_served',
                'beneficiary_email',
                'idempotency_key',
                'direction',
                'is_virtual_account',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('commission_payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
