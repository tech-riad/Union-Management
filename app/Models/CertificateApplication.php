<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateApplication extends Model
{
    protected $table = 'applications';
    
    protected $fillable = [
        'user_id',
        'certificate_type_id',
        'invoice_id',
        'fields',
        'form_data',
        'fee',
        'status',
        'payment_status',
        'approved_by',    // ✅ এগুলো যোগ করুন
        'rejected_by',    // ✅ এগুলো যোগ করুন
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'certificate_number',
        'paid_at',
        'remarks',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'fields' => 'array',
        'form_data' => 'array',
        'fee' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function certificateType(): BelongsTo
    {
        return $this->belongsTo(CertificateType::class, 'certificate_type_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // ✅ নতুন relationships যোগ করুন
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }
    
    // Custom accessors
    public function getApplicantNameAttribute()
    {
        if (is_array($this->form_data)) {
            if (isset($this->form_data['name_bangla'])) {
                return $this->form_data['name_bangla'];
            }
            if (isset($this->form_data['name_english'])) {
                return $this->form_data['name_english'];
            }
        }
        
        return $this->user ? $this->user->name : 'Unknown Applicant';
    }
    
    public function getFormattedFeeAttribute()
    {
        return '৳' . number_format($this->fee, 2);
    }
    
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];
        
        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    
    public function getPaymentBadgeClassAttribute()
    {
        $classes = [
            'paid' => 'bg-green-100 text-green-800',
            'unpaid' => 'bg-red-100 text-red-800',
        ];
        
        return $classes[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }
    
    public function getApplicationNoAttribute()
    {
        return 'APP-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
    
    public function getCertificateIdAttribute()
    {
        return $this->certificate_type_id;
    }
    
    public function setCertificateIdAttribute($value)
    {
        $this->attributes['certificate_type_id'] = $value;
    }
}