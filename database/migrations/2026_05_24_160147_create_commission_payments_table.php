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

            $table->string('metode')->nullable();

            $table->string('metode_pembayaran')->nullable();

            $table->decimal('jumlah', 15, 2);

            $table->string('invoice_id')->nullable();

            $table->string('invoice_payment_url')->nullable();

            $table->string('invoice_status')->nullable();

            $table->string('disbursement_id')->nullable();

            $table->string('disbursement_status')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_payments');
    }
};