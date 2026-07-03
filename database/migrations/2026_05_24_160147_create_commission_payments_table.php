<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_payments', function (Blueprint $table) {

            $table->id();

            $table->foreignId('commission_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('tanggal_bayar');

            $table->decimal('jumlah', 15, 2);
            $table->string('flip_disbursement_id', 30)->nullable();
            $table->string('disbursement_status', 20)->nullable();
            $table->string('account_holder', 100)->nullable();
            $table->string('bank_code', 30)->nullable();
            $table->string('account_number', 30)->nullable();
            $table->string('recipient_name', 100)->nullable();
            $table->string('sender_bank', 30)->nullable();
            $table->string('remark', 100)->nullable();
            $table->string('receipt', 255)->nullable();
            $table->timestamp('time_served')->nullable();
            $table->integer('fee')->nullable();
            $table->string('beneficiary_email', 150)->nullable();
            $table->string('idempotency_key', 100)->nullable()->unique();
            $table->string('direction', 30)->nullable();
            $table->boolean('is_virtual_account')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};