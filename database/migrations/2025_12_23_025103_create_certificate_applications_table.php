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
        Schema::create('certificate_applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('certificate_id')->constrained()->cascadeOnDelete();

    $table->string('application_no')->unique();

    $table->enum('status', [
        'pending',
        'verified',
        'approved',
        'rejected'
    ])->default('pending');

    $table->text('remarks')->nullable();

    $table->timestamp('approved_at')->nullable();

    $table->timestamps();
});

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_applications');
    }
};
