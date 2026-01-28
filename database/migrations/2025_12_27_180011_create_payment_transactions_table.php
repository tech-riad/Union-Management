<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('gateway'); // bkash, amarpay
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->unique(); // Our internal transaction ID
            $table->string('gateway_transaction_id')->nullable(); // Gateway's transaction ID
            $table->string('status')->default('initiated'); // initiated, pending, processing, completed, failed, cancelled, refunded
            $table->json('payload')->nullable(); // Additional data sent to gateway
            $table->json('gateway_response')->nullable(); // Response from gateway
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            
            $table->index(['transaction_id']);
            $table->index(['gateway_transaction_id']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}