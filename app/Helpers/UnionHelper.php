<?php

namespace App\Helpers;

use App\Models\UnionSetting;

class UnionHelper
{
    /**
     * Get union settings
     */
    public static function getSettings()
    {
        return UnionSetting::getSettings();
    }

    /**
     * Get union name (with fallback)
     */
    public static function getName($lang = 'bn')
    {
        $settings = self::getSettings();
        if ($lang === 'en') {
            return $settings->union_name ?? 'Union Digital Platform';
        }
        return $settings->union_name_bangla ?? 'ইউনিয়ন ডিজিটাল';
    }

    /**
     * Get union contact number (with fallback)
     */
    public static function getContactNumber()
    {
        $settings = self::getSettings();
        return $settings->contact_number ?? '১৬৩৪৫';
    }

    /**
     * Get union emergency number (with fallback)
     */
    public static function getEmergencyNumber()
    {
        $settings = self::getSettings();
        return $settings->emergency_number ?? '৯৯৯';
    }

    /**
     * Get union email (with fallback)
     */
    public static function getEmail()
    {
        $settings = self::getSettings();
        return $settings->email ?? 'support@uniondigital.gov';
    }

    /**
     * Get union address
     */
    public static function getAddress($lang = 'bn')
    {
        $settings = self::getSettings();
        if ($lang === 'en') {
            return $settings->address ?? 'North Gabtali, Gabtali Upazila, Bogura';
        }
        return $settings->address_bangla ?? 'উত্তর গাবতলী, গাবতলী উপজেলা, বগুড়া';
    }

    /**
     * Get logo URL
     */
    public static function getLogoUrl()
    {
        $settings = self::getSettings();
        return $settings->logo_url ?? asset('images/default-logo.png');
    }

    /**
     * Get favicon URL
     */
    public static function getFaviconUrl()
    {
        $settings = self::getSettings();
        return $settings->favicon_url ?? asset('images/default-favicon.png');
    }

    /**
     * Get chairman name
     */
    public static function getChairmanName()
    {
        $settings = self::getSettings();
        return $settings->chairman_name ?? 'মোঃ রফিকুল ইসলাম';
    }

    /**
     * Get secretary name
     */
    public static function getSecretaryName()
    {
        $settings = self::getSettings();
        return $settings->secretary_name ?? 'মোঃ আব্দুল করিম';
    }

    /**
     * Get chairman signature URL
     */
    public static function getChairmanSignatureUrl()
    {
        $settings = self::getSettings();
        return $settings->chairman_signature_url ?? null;
    }

    /**
     * Get chairman seal URL
     */
    public static function getChairmanSealUrl()
    {
        $settings = self::getSettings();
        return $settings->chairman_seal_url ?? null;
    }

    /**
     * Get social media links
     */
    public static function getSocialLinks()
    {
        $settings = self::getSettings();
        return [
            'website' => $settings->website ?? '#',
            'facebook' => $settings->facebook ?? '#',
            'twitter' => $settings->twitter ?? '#',
            'youtube' => $settings->youtube ?? '#',
        ];
    }

    /**
     * Get primary color (with fallback)
     */
    public static function getPrimaryColor()
    {
        $settings = self::getSettings();
        return $settings->primary_color ?? '#6366f1';
    }

    /**
     * Get secondary color (with fallback)
     */
    public static function getSecondaryColor()
    {
        $settings = self::getSettings();
        return $settings->secondary_color ?? '#10b981';
    }

    /**
     * Get accent color (with fallback)
     */
    public static function getAccentColor()
    {
        $settings = self::getSettings();
        return $settings->accent_color ?? '#8b5cf6';
    }

    /**
     * Get office hours
     */
    public static function getOfficeHours()
    {
        $settings = self::getSettings();
        if ($settings->office_start_time && $settings->office_end_time) {
            return date('h:i A', strtotime($settings->office_start_time)) . ' - ' . 
                   date('h:i A', strtotime($settings->office_end_time));
        }
        return '৯:০০ AM - ৫:০০ PM';
    }

    /**
     * Get working days
     */
    public static function getWorkingDays()
    {
        $settings = self::getSettings();
        return $settings->working_days ?? 'রবিবার - বৃহস্পতিবার';
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        $settings = self::getSettings();
        return $settings->maintenance_mode ?? false;
    }

    /**
     * Get maintenance message
     */
    public static function getMaintenanceMessage()
    {
        $settings = self::getSettings();
        return $settings->maintenance_message ?? 'সিস্টেম রক্ষণাবেক্ষণ চলছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
    }

    /**
     * Adjust color brightness
     */
    public static function adjustColor($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . 
                   str_repeat(substr($hex, 1, 1), 2) . 
                   str_repeat(substr($hex, 2, 1), 2);
        }
        
        if (!preg_match('/^[a-f0-9]{6}$/i', $hex)) {
            return $percent > 0 ? '#c7d2fe' : '#4f46e5';
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = max(0, min(255, $r + $r * $percent / 100));
        $g = max(0, min(255, $g + $g * $percent / 100));
        $b = max(0, min(255, $b + $b * $percent / 100));
        
        $r_hex = str_pad(dechex(round($r)), 2, '0', STR_PAD_LEFT);
        $g_hex = str_pad(dechex(round($g)), 2, '0', STR_PAD_LEFT);
        $b_hex = str_pad(dechex(round($b)), 2, '0', STR_PAD_LEFT);
        
        return '#' . $r_hex . $g_hex . $b_hex;
    }

    /**
     * Convert hex color to RGB
     */
    public static function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex, 0, 1), 2) . 
                   str_repeat(substr($hex, 1, 1), 2) . 
                   str_repeat(substr($hex, 2, 1), 2);
        }
        
        if (!preg_match('/^[a-f0-9]{6}$/i', $hex)) {
            return '99,102,241'; // #6366f1 in RGB
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "$r,$g,$b";
    }

    /**
     * Get primary color variants for CSS
     */
    public static function getPrimaryColorVariants()
    {
        $primary = self::getPrimaryColor();
        
        return [
            '50' => self::adjustColor($primary, 40),
            '100' => self::adjustColor($primary, 30),
            '200' => self::adjustColor($primary, 20),
            '300' => self::adjustColor($primary, 10),
            '400' => self::adjustColor($primary, 5),
            '500' => $primary,
            '600' => self::adjustColor($primary, -10),
            '700' => self::adjustColor($primary, -20),
            '800' => self::adjustColor($primary, -30),
            '900' => self::adjustColor($primary, -40),
        ];
    }

    /**
     * Generate dynamic CSS variables
     */
    public static function generateCssVariables()
    {
        $primary = self::getPrimaryColor();
        $secondary = self::getSecondaryColor();
        $accent = self::getAccentColor();
        $variants = self::getPrimaryColorVariants();
        
        $css = ":root {\n";
        
        // Base colors
        $css .= "  --primary: {$primary};\n";
        $css .= "  --secondary: {$secondary};\n";
        $css .= "  --accent: {$accent};\n";
        
        // Primary variants
        foreach ($variants as $key => $value) {
            $css .= "  --primary-{$key}: {$value};\n";
        }
        
        // RGB values
        $css .= "  --primary-rgb: " . self::hexToRgb($primary) . ";\n";
        $css .= "  --secondary-rgb: " . self::hexToRgb($secondary) . ";\n";
        $css .= "  --primary-light-rgb: " . self::hexToRgb($variants['100']) . ";\n";
        
        // Gradient definitions
        $css .= "  --gradient-primary: linear-gradient(135deg, {$primary} 0%, {$accent} 100%);\n";
        $css .= "  --gradient-secondary: linear-gradient(135deg, {$secondary} 0%, {$primary} 100%);\n";
        
        $css .= "}\n\n";
        
        // Generate dynamic CSS classes
        $css .= "/* Dynamic Classes */\n";
        $css .= ".gradient-primary { background: var(--gradient-primary); }\n";
        $css .= ".gradient-secondary { background: var(--gradient-secondary); }\n";
        $css .= ".text-primary { color: var(--primary-500); }\n";
        $css .= ".bg-primary { background-color: var(--primary-500); }\n";
        $css .= ".border-primary { border-color: var(--primary-500); }\n";
        
        return $css;
    }

    /**
     * Generate inline style tag with dynamic CSS
     */
    public static function generateDynamicStyles()
    {
        $css = self::generateCssVariables();
        return "<style id='dynamic-styles'>\n{$css}\n</style>";
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings()
    {
        $settings = self::getSettings();
        
        return [
            'name' => self::getName(),
            'name_en' => self::getName('en'),
            'contact_number' => self::getContactNumber(),
            'emergency_number' => self::getEmergencyNumber(),
            'email' => self::getEmail(),
            'address' => self::getAddress(),
            'address_en' => self::getAddress('en'),
            'logo_url' => self::getLogoUrl(),
            'favicon_url' => self::getFaviconUrl(),
            'chairman_name' => self::getChairmanName(),
            'secretary_name' => self::getSecretaryName(),
            'social_links' => self::getSocialLinks(),
            'primary_color' => self::getPrimaryColor(),
            'secondary_color' => self::getSecondaryColor(),
            'accent_color' => self::getAccentColor(),
            'office_hours' => self::getOfficeHours(),
            'working_days' => self::getWorkingDays(),
            'is_maintenance' => self::isMaintenanceMode(),
            'maintenance_message' => self::getMaintenanceMessage(),
            'css_variables' => self::generateCssVariables(),
            'current_year' => date('Y'),
        ];
    }
}