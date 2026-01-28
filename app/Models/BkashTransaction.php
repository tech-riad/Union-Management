<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkashTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'payment_id',
        'trx_id',
        'status',
        'amount',
        'currency',
        'request_payload',
        'response_payload',
        'payment_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'payment_time' => 'datetime',
    ];

    /**
     * Get the invoice associated with the transaction.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed.
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Mark transaction as completed.
     */
    public function markAsCompleted($trxId, $responsePayload = null)
    {
        $this->update([
            'status' => 'completed',
            'trx_id' => $trxId,
            'response_payload' => $responsePayload,
            'payment_time' => now(),
        ]);
    }

    /**
     * Mark transaction as failed.
     */
    public function markAsFailed($responsePayload = null)
    {
        $this->update([
            'status' => 'failed',
            'response_payload' => $responsePayload,
        ]);
    }

    /**
     * Mark transaction as cancelled.
     */
    public function markAsCancelled($responsePayload = null)
    {
        $this->update([
            'status' => 'cancelled',
            'response_payload' => $responsePayload,
        ]);
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        return '৳ ' . number_format($this->amount, 2);
    }

    /**
     * Get payment status badge.
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge badge-warning',
            'completed' => 'badge badge-success',
            'failed' => 'badge badge-danger',
            'cancelled' => 'badge badge-secondary',
        ];

        $statusText = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ];

        $badgeClass = $badges[$this->status] ?? 'badge badge-secondary';
        $status = $statusText[$this->status] ?? $this->status;

        return '<span class="' . $badgeClass . '">' . $status . '</span>';
    }

    /**
     * Get payment date.
     */
    public function getPaymentDateAttribute()
    {
        return $this->payment_time ? $this->payment_time->format('d/m/Y h:i A') : 'N/A';
    }

    /**
     * Get human readable status.
     */
    public function getHumanStatusAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}