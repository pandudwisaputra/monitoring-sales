<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('periode');

            $table->decimal('total_penjualan', 15, 2);

            $table->decimal('persentase_komisi', 5, 2);

            $table->decimal('total_pembayaran', 15, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'disbursed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};