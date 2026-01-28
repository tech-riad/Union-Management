<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('id');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
        });
        
        // অন্যান্য টেবিলের জন্য একইভাবে যোগ করুন
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
        
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
        
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};