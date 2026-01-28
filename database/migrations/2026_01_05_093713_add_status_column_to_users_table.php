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
        Schema::table('users', function (Blueprint $table) {
            // status column যোগ করুন যদি না থাকে
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'banned'])
                    ->default('active')
                    ->after('role');
            }

            // অন্যান্য columns যদি প্রয়োজন হয়
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile', 20)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('mobile');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('username');
            }

            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('address');
            }

            if (!Schema::hasColumn('users', 'remarks')) {
                $table->text('remarks')->nullable()->after('profile_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback করলে columns drop করুন
            $columnsToDrop = ['status', 'mobile', 'username', 'address', 'profile_photo', 'remarks'];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};