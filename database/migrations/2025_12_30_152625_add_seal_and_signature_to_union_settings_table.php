<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('union_settings', function (Blueprint $table) {
            $table->string('chairman_signature')->nullable()->after('secretary_phone');
            $table->string('chairman_seal')->nullable()->after('chairman_signature');
            $table->string('secretary_signature')->nullable()->after('chairman_seal');
        });
    }

    public function down(): void
    {
        Schema::table('union_settings', function (Blueprint $table) {
            $table->dropColumn(['chairman_signature', 'chairman_seal', 'secretary_signature']);
        });
    }
};