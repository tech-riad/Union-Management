<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'gateway',
        'amount',
        'transaction_id',
        'gateway_transaction_id',
        'status',
        'payload',
        'gateway_response',
        'completed_at',
        'failed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'gateway_response' => 'array',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Get the invoice associated with the transaction.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return '৳ ' . number_format($this->amount, 2);
    }

    /**
     * Check if transaction is successful.
     */
    public function getIsSuccessfulAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Get transaction status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'initiated' => 'bg-blue-100 text-blue-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'refunded' => 'bg-indigo-100 text-indigo-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}