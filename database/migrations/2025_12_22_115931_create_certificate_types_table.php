<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certificate_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('fee', 10, 2)->default(0);
            $table->string('template')->nullable();
            $table->enum('validity', ['none','yearly'])->default('none');
	    $table->json('form_fields')->nullable()->default(json_encode([]));
	    $table->json('fields')->nullable()->default(json_encode([]));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_types');
    }
};
