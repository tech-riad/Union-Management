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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            // ---------------- PROFILE ----------------
            $table->string('profile_photo')->nullable();

            // ---------------- NAME ----------------
            $table->string('name_bn')->nullable();
            $table->string('name_en')->nullable();
            $table->string('father_name_bn')->nullable();
            $table->string('father_name_en')->nullable();
            $table->string('mother_name_bn')->nullable();
            $table->string('mother_name_en')->nullable();

            // ---------------- PERSONAL ----------------
            $table->date('dob')->nullable();
            $table->string('nid_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('religion')->nullable();
            $table->string('height')->nullable();
            $table->string('birth_mark')->nullable();
            $table->string('quota')->nullable();
            $table->string('profession')->nullable();
            $table->string('education')->nullable();

            // ---------------- ADDRESS ----------------
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
	    $table->string('village')->nullable();
	    $table->string('ward')->nullable();

	    // ---------------- OTHERS ----------------
	   $table->string('occupation')->nullable();

            // ---------------- STATUS ----------------
            $table->boolean('is_complete')->default(false);

            $table->timestamps();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
