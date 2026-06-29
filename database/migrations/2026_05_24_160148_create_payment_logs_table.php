<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('commission_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('order_id');

            $table->string('transaction_status');

            $table->longText('payload');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};