<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN, LOGOUT, APPROVE, REJECT, etc.
            $table->string('module'); // USER, APPLICATION, CERTIFICATE, PAYMENT, SETTINGS, etc.
            $table->text('description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};