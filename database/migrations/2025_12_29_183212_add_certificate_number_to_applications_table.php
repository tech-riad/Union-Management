<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('invoice_id');
            }
            
            // অন্যান্য missing columns যোগ করুন
            $columnsToAdd = [
                'remarks' => ['type' => 'text', 'nullable' => true],
                'approved_at' => ['type' => 'timestamp', 'nullable' => true],
                'approved_by' => ['type' => 'foreignId', 'nullable' => true, 'references' => 'users'],
                'rejected_at' => ['type' => 'timestamp', 'nullable' => true],
                'rejected_by' => ['type' => 'foreignId', 'nullable' => true, 'references' => 'users'],
                'rejection_reason' => ['type' => 'text', 'nullable' => true],
                'paid_at' => ['type' => 'timestamp', 'nullable' => true],
            ];
            
            foreach ($columnsToAdd as $column => $config) {
                if (!Schema::hasColumn('applications', $column)) {
                    if ($config['type'] === 'foreignId') {
                        $table->foreignId($column)->nullable()->constrained('users');
                    } else {
                        $table->{$config['type']}($column)->nullable();
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            // Rollback এর জন্য
            $table->dropColumn([
                'certificate_number',
                'remarks',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by',
                'rejection_reason',
                'paid_at'
            ]);
        });
    }
};