<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('certificate_types', function (Blueprint $table) {
            if (!Schema::hasColumn('certificate_types', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('validity');
            }
        });
    }

    public function down()
    {
        Schema::table('certificate_types', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};