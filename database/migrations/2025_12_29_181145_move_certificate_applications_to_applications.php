<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Check if certificate_applications table has data
        $hasData = DB::table('certificate_applications')->count() > 0;
        
        if ($hasData) {
            // Copy data from certificate_applications to applications
            DB::statement("
                INSERT INTO applications (user_id, certificate_type_id, form_data, fee, status, payment_status, created_at, updated_at)
                SELECT 
                    user_id,
                    certificate_id as certificate_type_id,
                    form_data,
                    fee,
                    status,
                    payment_status,
                    created_at,
                    updated_at
                FROM certificate_applications
            ");
            
            // Update invoice foreign keys
            DB::statement("
                UPDATE invoices i
                JOIN certificate_applications ca ON i.application_id = ca.id
                SET i.application_id = ca.id
                WHERE i.application_id = ca.id
            ");
        }
        
        // Optional: Drop certificate_applications table যদি চান
        // Schema::dropIfExists('certificate_applications');
    }

    public function down()
    {
        // Revert if needed
        DB::statement("
            INSERT INTO certificate_applications (user_id, certificate_id, form_data, fee, status, payment_status, created_at, updated_at)
            SELECT 
                user_id,
                certificate_type_id as certificate_id,
                form_data,
                fee,
                status,
                payment_status,
                created_at,
                updated_at
            FROM applications
        ");
    }
};