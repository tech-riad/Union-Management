<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('role')->default('admin'); // super_admin, admin, secretary
            
            // Since single union, no need for union_id
            // But we can keep ward_id for ward-based admins
            $table->unsignedBigInteger('ward_id')->nullable();
            
            // Personal Info
            $table->string('designation')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo')->nullable();
            
            // Status
            $table->boolean('status')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            
            // Permissions (JSON format for custom permissions)
            $table->json('permissions')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('role');
            $table->index('status');
            $table->index('ward_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};