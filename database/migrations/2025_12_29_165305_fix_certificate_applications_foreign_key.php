<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificate_applications', function (Blueprint $table) {
            // Existing foreign key drop করুন যদি থাকে
            $table->dropForeign(['certificate_id']);
            
            // New foreign key যোগ করুন certificate_types table এর সাথে
            $table->foreign('certificate_id')
                  ->references('id')
                  ->on('certificate_types')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('certificate_applications', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            
            // Original foreign key restore করুন (certificates table এর সাথে)
            $table->foreign('certificate_id')
                  ->references('id')
                  ->on('certificates')
                  ->onDelete('cascade');
        });
    }
};