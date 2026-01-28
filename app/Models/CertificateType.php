<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    protected $fillable = [
        'name',
        'fee',
        'template',
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
        'dimensions',
        'validity',
        'form_fields',
    ];

    protected $casts = [
        'form_fields' => 'array',
        'template_config' => 'array',
        'pdf_settings' => 'array',
        'signatures' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $attributes = [
        'form_fields' => '[]',
        'dimensions' => '210,297',
        'template_type' => 'file',
        'pdf_settings' => '{
            "paper_size": "A4",
            "orientation": "portrait",
            "margin_top": 15,
            "margin_bottom": 15,
            "margin_left": 15,
            "margin_right": 15,
            "header_enabled": true,
            "footer_enabled": true,
            "watermark_enabled": false,
            "watermark_opacity": 0.1,
            "default_font": "SolaimanLipi",
            "font_size": 12,
            "line_height": 1.5
        }'
    ];

    /**
     * Template file এর full path পাওয়ার জন্য
     */
    public function getTemplateFilePathAttribute()
    {
        if ($this->template_file) {
            if ($this->template_path) {
                return rtrim($this->template_path, '/') . '/' . $this->template_file;
            }
            return 'certificate_templates/' . $this->template_file;
        }
        return null;
    }

    /**
     * View path পাওয়ার জন্য
     */
    public function getTemplateViewPathAttribute()
    {
        if ($this->template_file) {
            // Remove .blade.php extension if present
            $filename = str_replace('.blade.php', '', $this->template_file);
            return 'certificate_templates.' . $filename;
        }
        return 'certificate_templates.default';
    }

    /**
     * Watermark full path
     */
    public function getWatermarkFullPathAttribute()
    {
        if ($this->watermark_path) {
            return public_path($this->watermark_path);
        }
        return null;
    }

    /**
     * Certificate dimensions array হিসেবে পাওয়ার জন্য
     */
    public function getDimensionsArrayAttribute()
    {
        $dimensions = explode(',', $this->dimensions);
        return [
            'width' => isset($dimensions[0]) ? (int) $dimensions[0] : 210,
            'height' => isset($dimensions[1]) ? (int) $dimensions[1] : 297
        ];
    }

    /**
     * Paper size and orientation for PDF
     */
    public function getPaperSizeAttribute()
    {
        $settings = $this->pdf_settings;
        return [
            'size' => $settings['paper_size'] ?? 'A4',
            'orientation' => $settings['orientation'] ?? 'portrait'
        ];
    }

    /**
     * Signature names array
     */
    public function getSignatureNamesAttribute()
    {
        if (is_array($this->signatures) && count($this->signatures) > 0) {
            return $this->signatures;
        }
        
        // Default signatures based on template
        $defaults = [
            'চেয়ারম্যান',
            'সচিব'
        ];
        
        return $defaults;
    }

    /**
     * Get PDF settings with defaults
     */
    public function getPdfSettingsAttribute($value)
    {
        $defaultSettings = [
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
            'header_enabled' => true,
            'footer_enabled' => true,
            'watermark_enabled' => false,
            'watermark_opacity' => 0.1,
            'default_font' => 'SolaimanLipi',
            'font_size' => 12,
            'line_height' => 1.5
        ];

        if ($value) {
            $settings = json_decode($value, true);
            if (is_array($settings)) {
                return array_merge($defaultSettings, $settings);
            }
        }
        
        return $defaultSettings;
    }

    /**
     * Set PDF settings attribute
     */
    public function setPdfSettingsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['pdf_settings'] = json_encode($value);
        } else {
            $this->attributes['pdf_settings'] = $value;
        }
    }

    /**
     * Set signatures attribute
     */
    public function setSignaturesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['signatures'] = json_encode($value);
        } else {
            $this->attributes['signatures'] = $value;
        }
    }

    /**
     * Set template config attribute
     */
    public function setTemplateConfigAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['template_config'] = json_encode($value);
        } else {
            $this->attributes['template_config'] = $value;
        }
    }

    /**
     * Get template display name
     */
    public function getDisplayTemplateAttribute()
    {
        $templateMap = [
            'নাগরিকত্ব সনদ' => 'citizenship',
            'ট্রেড লাইসেন্স' => 'trade-license',
            'ওয়ারিশান সনদ' => 'inheritance',
            'ভূমিহীন সনদ' => 'landless',
            'পারিবারিক সনদ' => 'family',
            'অবিবাহিত সনদ' => 'unmarried',
            'পুনর্বিবাহ না হওয়া সনদ' => 'remarriage',
            'একই নামের প্রত্যয়ন' => 'same-name',
            'প্রতিবন্ধী সনদপত্র' => 'disabled',
            'অর্থনৈতিক অসচ্ছলতার সনদপত্র' => 'economic',
            'বিবাহিত সনদ' => 'married',
            'দ্বিতীয় বিবাহের অনুমতি পত্র' => 'second-marriage',
            'নতুন ভোটার প্রত্যয়ন' => 'new-voter',
            'জাতীয়তা সনদ' => 'nationality',
            'এতিম সনদ' => 'orphan',
            'মাসিক আয়ের সনদ' => 'monthly-income'
        ];

        return $templateMap[$this->template] ?? 'default';
    }

    /**
     * Relationships
     */
    public function applications()
    {
        return $this->hasMany(\App\Models\CertificateApplication::class);
    }

    /**
     * Check if template file exists
     */
    public function templateFileExists()
    {
        if ($this->template_file) {
            $viewPath = $this->getTemplateViewPathAttribute();
            return view()->exists($viewPath);
        }
        return false;
    }

    /**
     * Get validity in days
     */
    public function getValidityInDays()
    {
        if ($this->validity === 'yearly') {
            return $this->validity_days ?? 365;
        }
        return null; // No expiry
    }

    /**
     * Generate certificate number
     */
    public function generateCertificateNumber($serial)
    {
        $prefix = $this->serial_prefix ?? 'CERT';
        $year = date('Y');
        $month = date('m');
        
        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $serial);
    }
}