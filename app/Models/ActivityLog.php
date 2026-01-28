<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime:d-m-Y h:i:s A',
        'updated_at' => 'datetime:d-m-Y h:i:s A'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted action with badge
     */
    public function getActionBadgeAttribute(): string
    {
        $badges = [
            'CREATE' => 'success',
            'UPDATE' => 'primary',
            'DELETE' => 'danger',
            'LOGIN' => 'info',
            'LOGOUT' => 'secondary',
            'APPROVE' => 'success',
            'REJECT' => 'danger',
            'PAYMENT' => 'warning',
            'DOWNLOAD' => 'dark'
        ];

        $color = $badges[$this->action] ?? 'secondary';
        return "<span class='badge bg-$color'>$this->action</span>";
    }

    /**
     * Get formatted module name
     */
    public function getModuleFormattedAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->module));
    }
}