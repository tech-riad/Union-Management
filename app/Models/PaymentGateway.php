<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array', // This automatically handles JSON decode/encode
    ];

    /**
     * Find gateway by name
     */
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }

    /**
     * Check if gateway is active
     */
    public static function isActive($name)
    {
        $gateway = static::findByName($name);
        return $gateway && $gateway->is_active;
    }

    /**
     * Get settings value
     */
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Update settings
     */
    public function updateSetting($key, $value)
    {
        $settings = $this->settings;
        $settings[$key] = $value;
        $this->settings = $settings;
        return $this->save();
    }
}