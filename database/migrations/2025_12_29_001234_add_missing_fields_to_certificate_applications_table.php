<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificate_applications', function (Blueprint $table) {
            // Jodi field gulo na thake
            if (!Schema::hasColumn('certificate_applications', 'form_data')) {
                $table->json('form_data')->nullable()->after('application_no');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'fee')) {
                $table->decimal('fee', 10, 2)->default(0)->after('form_data');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'payment_status')) {
                $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid')->after('fee');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
            
            if (!Schema::hasColumn('certificate_applications', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->constrained('users');
            }
            
            if (!Schema::hasColumn('certificate_applications', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
            
            if (!Schema::hasColumn('certificate_applications', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('certificate_applications', function (Blueprint $table) {
            // Reverse korar jonno
            $columns = [
                'form_data', 'fee', 'payment_status', 'certificate_number',
                'approved_by', 'rejected_at', 'rejected_by', 'rejection_reason', 'paid_at'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('certificate_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};