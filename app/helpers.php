<?php

namespace App\Helpers;

class PdfHelper
{
    public static function banglaNumber($number)
    {
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];
        $bangla = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '.'];
        return str_replace($english, $bangla, number_format($number, 2));
    }

    public static function banglaDate($date)
    {
        if (!$date) return 'তারিখ নেই';
        
        $banglaMonths = [
            'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
            'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
        ];
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        $day = self::banglaNumber($date->day);
        $month = $banglaMonths[$date->month - 1] ?? 'মাস';
        $year = self::banglaNumber($date->year);
        
        return $day . ' ' . $month . ' ' . $year;
    }

    public static function formatCurrency($amount)
    {
        return self::banglaNumber($amount) . ' ৳';
    }
}