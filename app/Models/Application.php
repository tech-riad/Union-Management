<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'certificate_type_id',
        'form_data',
        'fee',
        'status',
        'payment_status',
        'certificate_number', // নতুন field যোগ করুন
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'remarks',
        'paid_at'
    ];

    protected $casts = [
        'form_data' => 'array', // automatic JSON cast
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
        'fee' => 'decimal:2'
    ];

    // Certificate Type relation
    public function certificateType()
    {
        return $this->belongsTo(CertificateType::class, 'certificate_type_id');
    }

    // Invoice relation
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // User relation (who applied)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Approved By User relation
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Rejected By User relation
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved applications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for paid applications
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for unpaid applications
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Check if application is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if application is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if payment is paid
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'approved':
                return 'success';
            case 'rejected':
                return 'danger';
            case 'pending':
                return 'warning';
            default:
                return 'secondary';
        }
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusBadgeAttribute()
    {
        switch ($this->payment_status) {
            case 'paid':
                return 'success';
            case 'unpaid':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Generate certificate number
     */
    public function generateCertificateNumber()
    {
        if (!$this->certificate_number) {
            $this->certificate_number = 'CERT-' . date('Ymd') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
            $this->save();
        }
        return $this->certificate_number;
    }
}