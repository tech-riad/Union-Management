<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'union_name',
        'union_name_bangla',
        'contact_number',
        'emergency_number',
        'email',
        'address',
        'address_bangla',
        'logo',
        'favicon',
        'website',
        'facebook',
        'twitter',
        'youtube',
        'office_start_time',
        'office_end_time',
        'working_days',
        'chairman_name',
        'chairman_phone',
        'chairman_signature',
        'chairman_seal',
        'secretary_name',
        'secretary_phone',
        'secretary_signature',
        'about_us',
        'terms_conditions',
        'privacy_policy',
        'primary_color',
        'secondary_color',
        'maintenance_mode',
        'currency',
        'timezone',
        'date_format',
        'time_format',
    ];

    protected $casts = [
        'office_start_time' => 'datetime:H:i',
        'office_end_time' => 'datetime:H:i',
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Get single instance of union settings
     */
    public static function getSettings()
    {
        static $settings = null;
        
        if ($settings === null) {
            $settings = self::first();
            
            if (!$settings) {
                // Create default settings with ALL fillable fields
                $settings = self::create([
                    // Basic Information
                    'union_name' => 'Union Digital Platform',
                    'union_name_bangla' => 'ইউনিয়ন ডিজিটাল প্ল্যাটফর্ম',
                    'contact_number' => '১৬৩৪৫',
                    'emergency_number' => '৯৯৯',
                    'email' => 'support@uniondigital.gov',
                    
                    // Address
                    'address' => 'North Gabtali, Gabtali Upazila, Bogura',
                    'address_bangla' => 'উত্তর গাবতলী, গাবতলী উপজেলা, বগুড়া',
                    
                    // Media Files (these are the actual database fields)
                    'logo' => null,
                    'favicon' => null,
                    'chairman_signature' => null,
                    'chairman_seal' => null,
                    'secretary_signature' => null,
                    
                    // Social Media
                    'website' => '#',
                    'facebook' => '#',
                    'twitter' => '#',
                    'youtube' => '#',
                    
                    // Office Hours
                    'office_start_time' => '09:00:00',
                    'office_end_time' => '17:00:00',
                    'working_days' => 'Sunday-Thursday',
                    
                    // Chairman Information
                    'chairman_name' => 'মোঃ রফিকুল ইসলাম',
                    'chairman_phone' => '০১৭১২৩৪৫৬৭৮',
                    
                    // Secretary Information
                    'secretary_name' => 'মোঃ আব্দুল করিম',
                    'secretary_phone' => '০১৮১২৩৪৫৬৭৮',
                    
                    // Content
                    'about_us' => 'ডিজিটাল বাংলাদেশের অংশ হিসেবে আমাদের ইউনিয়ন ডিজিটাল সেবা প্রদান করছে। আমরা স্বচ্ছতা, জবাবদিহিতা এবং দক্ষতার মাধ্যমে নাগরিক সেবা নিশ্চিত করি।',
                    'terms_conditions' => '১. সকল আবেদন সঠিক তথ্য দিয়ে পূরণ করতে হবে।\n২. ভুল তথ্য প্রদানের জন্য আবেদন বাতিল হতে পারে।\n৩. সকল পেমেন্ট অনলাইন সিস্টেমের মাধ্যমে সম্পন্ন করতে হবে।',
                    'privacy_policy' => 'আপনার ব্যক্তিগত তথ্য আমাদের গোপনীয়তা নীতির আওতায় সুরক্ষিত। আমরা আপনার তথ্য তৃতীয় পক্ষের সাথে শেয়ার করি না।',
                    
                    // Colors
                    'primary_color' => '#6366f1',  // Tailwind indigo-500
                    'secondary_color' => '#10b981', // Tailwind emerald-500
                    
                    // System Settings
                    'maintenance_mode' => false,
                    'currency' => 'BDT',
                    'timezone' => 'Asia/Dhaka',
                    'date_format' => 'd/m/Y',
                    'time_format' => 'h:i A',
                ]);
            }
        }
        
        return $settings;
    }

    /**
     * Get logo URL (Accessor)
     */
    public function getLogoUrlAttribute()
    {
        return $this->getFileUrl($this->logo, 'images/default-logo.png');
    }

    /**
     * Get favicon URL (Accessor)
     */
    public function getFaviconUrlAttribute()
    {
        return $this->getFileUrl($this->favicon, 'images/default-favicon.png');
    }

    /**
     * Get chairman signature URL (Accessor)
     */
    public function getChairmanSignatureUrlAttribute()
    {
        return $this->getFileUrl($this->chairman_signature, 'images/default-signature.png');
    }

    /**
     * Get chairman seal URL (Accessor)
     */
    public function getChairmanSealUrlAttribute()
    {
        return $this->getFileUrl($this->chairman_seal, 'images/default-seal.png');
    }

    /**
     * Get secretary signature URL (Accessor)
     */
    public function getSecretarySignatureUrlAttribute()
    {
        return $this->getFileUrl($this->secretary_signature, 'images/default-signature.png');
    }

    /**
     * Helper method to get file URL
     */
    private function getFileUrl($filename, $default = null)
    {
        if ($filename) {
            // Check if file exists in storage
            if (\Storage::disk('public')->exists($filename)) {
                return \Storage::disk('public')->url($filename);
            }
            
            // Check if file exists in public directory (for default images)
            if (file_exists(public_path($filename))) {
                return asset($filename);
            }
        }
        
        return $default ? asset($default) : null;
    }

    /**
     * Get office hours (Accessor)
     */
    public function getOfficeHoursAttribute()
    {
        if ($this->office_start_time && $this->office_end_time) {
            return date('h:i A', strtotime($this->office_start_time)) . ' - ' . 
                   date('h:i A', strtotime($this->office_end_time));
        }
        return '09:00 AM - 05:00 PM';
    }
}