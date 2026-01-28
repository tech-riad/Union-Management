<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // বিদ্যমান ফিল্ড থাকলে না লাগানো
            if (!Schema::hasColumn('invoices', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('invoices', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('transaction_id');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('transaction_id');
            }
            
            if (!Schema::hasColumn('invoices', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_details');
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'transaction_id', 'payment_details', 'payment_gateway', 'paid_at']);
        });
    }
}