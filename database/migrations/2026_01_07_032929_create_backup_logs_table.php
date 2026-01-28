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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->enum('type', ['full', 'database', 'files', 'auto'])->default('auto');
            $table->string('size');
            $table->string('path');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
            $table->index(['type', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};