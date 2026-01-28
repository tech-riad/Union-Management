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
        Schema::table('certificate_types', function (Blueprint $table) {
            // Template ফাইল এর জন্য নতুন কলাম
            $table->string('template_file')->nullable()->after('template');
            
            // Template HTML content এর জন্য (Optional)
            $table->text('template_html')->nullable()->after('template_file');
            
            // Template variables/configuration এর জন্য
            $table->json('template_config')->nullable()->after('template_html');
            
            // Template type (file/html)
            $table->enum('template_type', ['file', 'html'])->default('file')->after('template_config');
            
            // Template path/directory
            $table->string('template_path')->nullable()->after('template_type');
            
            // Watermark image path (Optional)
            $table->string('watermark_path')->nullable()->after('template_path');
            
            // Header/Footer settings (JSON)
            $table->json('pdf_settings')->nullable()->after('watermark_path');
            
            // Certificate serial number prefix
            $table->string('serial_prefix')->nullable()->after('pdf_settings');
            
            // Certificate validity days (if yearly)
            $table->integer('validity_days')->nullable()->after('serial_prefix');
            
            // Required signatures (JSON array)
            $table->json('signatures')->nullable()->after('validity_days');
            
            // Certificate dimensions (width, height in mm)
            $table->string('dimensions')->default('210,297')->after('signatures'); // A4 size default
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_types', function (Blueprint $table) {
            $table->dropColumn([
                'template_file',
                'template_html',
                'template_config',
                'template_type',
                'template_path',
                'watermark_path',
                'pdf_settings',
                'serial_prefix',
                'validity_days',
                'signatures',
                'dimensions'
            ]);
        });
    }
};