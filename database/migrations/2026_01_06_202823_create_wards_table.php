<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bn_name')->nullable();
            $table->string('number')->nullable(); // Ward number like 1, 2, 3...
            $table->text('description')->nullable();
            $table->string('ward_member_name')->nullable();
            $table->string('ward_member_phone')->nullable();
            $table->string('ward_member_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('total_citizens')->default(0);
            $table->integer('total_applications')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('number');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};