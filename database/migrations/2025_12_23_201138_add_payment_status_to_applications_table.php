<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add payment_status column if it doesn't exist
            if (!Schema::hasColumn('applications', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('status');
            }
            
            // Add invoice_id column if you need to link with invoices
            if (!Schema::hasColumn('applications', 'invoice_id')) {
                $table->unsignedBigInteger('invoice_id')->nullable()->after('certificate_type_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Remove the columns if rollback needed
            $table->dropColumn('payment_status');
            $table->dropColumn('invoice_id');
        });
    }
};