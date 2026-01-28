<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('union_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('union_name')->nullable();
            $table->string('union_name_bangla')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('emergency_number')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('address_bangla')->nullable();
            
            // Media
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('banner_image')->nullable();
            
            // Office Hours
            $table->time('office_start_time')->default('09:00:00');
            $table->time('office_end_time')->default('17:00:00');
            $table->string('working_days')->default('Sunday-Thursday');
            $table->text('holiday_schedule')->nullable();
            
            // Officials
            $table->string('chairman_name')->nullable();
            $table->string('chairman_name_bangla')->nullable();
            $table->string('chairman_phone')->nullable();
            $table->string('chairman_image')->nullable();
            $table->string('secretary_name')->nullable();
            $table->string('secretary_name_bangla')->nullable();
            $table->string('secretary_phone')->nullable();
            $table->string('secretary_image')->nullable();
            
            // Content
            $table->text('about_us')->nullable();
            $table->text('about_us_bangla')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('terms_conditions_bangla')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('privacy_policy_bangla')->nullable();
            $table->text('vision_mission')->nullable();
            $table->text('organizational_structure')->nullable();
            
            // Appearance
            $table->string('primary_color')->default('#3b82f6');
            $table->string('secondary_color')->default('#10b981');
            $table->string('accent_color')->default('#f59e0b');
            $table->string('text_color')->default('#1f2937');
            $table->string('background_color')->default('#ffffff');
            
            // System Settings
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->string('currency')->default('BDT');
            $table->string('currency_symbol')->default('৳');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('date_format')->default('d F, Y');
            $table->string('time_format')->default('h:i A');
            $table->boolean('auto_backup')->default(true);
            $table->string('backup_frequency')->default('daily');
            
            // Payment Settings
            $table->boolean('bkash_enabled')->default(true);
            $table->boolean('nagad_enabled')->default(true);
            $table->boolean('rocket_enabled')->default(true);
            $table->boolean('bank_enabled')->default(true);
            $table->boolean('cash_enabled')->default(true);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_routing_number')->nullable();
            
            // Certificate Settings
            $table->boolean('certificate_auto_approve')->default(false);
            $table->integer('certificate_approval_time')->default(24); // in hours
            $table->boolean('certificate_qr_enabled')->default(true);
            $table->boolean('certificate_watermark')->default(true);
            $table->string('certificate_signature')->nullable();
            $table->string('certificate_seal')->nullable();
            $table->text('certificate_footer')->nullable();
            
            // SMS/Email Settings
            $table->boolean('sms_notification')->default(true);
            $table->boolean('email_notification')->default(true);
            $table->string('sms_provider')->nullable();
            $table->text('sms_api_key')->nullable();
            $table->text('sms_api_secret')->nullable();
            $table->string('email_provider')->nullable();
            $table->text('email_host')->nullable();
            $table->string('email_port')->nullable();
            $table->string('email_username')->nullable();
            $table->text('email_password')->nullable();
            $table->string('email_encryption')->nullable();
            $table->string('email_from_address')->nullable();
            $table->string('email_from_name')->nullable();
            
            // Ward Management (Since single union, we can store wards here)
            $table->text('wards')->nullable(); // JSON format: [{"id":1,"name":"ওয়ার্ড ১","bn_name":"ওয়ার্ড ১"},...]
            $table->integer('total_wards')->default(9);
            
            // Statistics (Can be auto-calculated)
            $table->integer('total_citizens')->default(0);
            $table->integer('total_applications')->default(0);
            $table->integer('total_certificates')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            
            // SEO Settings
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('google_analytics_id')->nullable();
            
            // Social Links
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('telegram')->nullable();
            
            // Map Location
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('google_map_embed')->nullable();
            
            // Additional Features
            $table->boolean('online_payment_enabled')->default(true);
            $table->boolean('certificate_verification_enabled')->default(true);
            $table->boolean('citizen_registration_enabled')->default(true);
            $table->boolean('online_application_enabled')->default(true);
            $table->boolean('complaint_system_enabled')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('union_settings');
    }
};