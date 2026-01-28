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
        Schema::create('bkash_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->string('payment_id')->nullable();
        $table->string('trx_id')->nullable();
        $table->string('status'); // pending, completed, failed, cancelled
        $table->decimal('amount', 10, 2);
        $table->string('currency')->default('BDT');
        $table->json('request_payload')->nullable();
        $table->json('response_payload')->nullable();
        $table->timestamp('payment_time')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bkash_transactions');
    }
};
