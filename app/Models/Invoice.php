<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Invoice extends Model
{
    /**
     * Payment status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';
    
    /**
     * Payment method constants
     */
    const METHOD_BKASH = 'bkash';
    const METHOD_ROCKET = 'rocket';
    const METHOD_NAGAD = 'nagad';
    const METHOD_CASH = 'cash';
    const METHOD_BANK = 'bank';
    const METHOD_OTHER = 'other';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'application_id',
        'user_id',
        'invoice_no',
        'amount',
        'payment_method',
        'payment_status', // আপনার টেবিলে এই column আছে
        'transaction_id',
        'payment_details',
        'paid_at',
        'due_date',
        'remarks',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'payment_details'
    ];

    /**
     * Accessor for status (to maintain compatibility)
     */
    public function getStatusAttribute()
    {
        return $this->payment_status;
    }

    /**
     * Mutator for status (to maintain compatibility)
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['payment_status'] = $value;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // Generate invoice number if not provided
            if (empty($invoice->invoice_no)) {
                $invoice->invoice_no = self::generateInvoiceNumber();
            }
            
            // Set default payment status
            if (empty($invoice->payment_status)) {
                $invoice->payment_status = self::STATUS_PENDING;
            }
            
            // Set created_by if not set
            if (empty($invoice->created_by) && auth()->check()) {
                $invoice->created_by = auth()->id();
            }
        });

        static::updating(function ($invoice) {
            // Log payment status changes
            if ($invoice->isDirty('payment_status')) {
                Log::info('Invoice payment status changed', [
                    'invoice_id' => $invoice->id,
                    'old_status' => $invoice->getOriginal('payment_status'),
                    'new_status' => $invoice->payment_status,
                    'changed_by' => auth()->id() ?? 'system'
                ]);
            }
            
            // Set paid_at timestamp when payment_status changes to paid
            if ($invoice->isDirty('payment_status') && 
                $invoice->payment_status === self::STATUS_PAID && 
                !$invoice->paid_at) {
                $invoice->paid_at = now();
            }
            
            // Set updated_by if not set
            if (empty($invoice->updated_by) && auth()->check()) {
                $invoice->updated_by = auth()->id();
            }
        });
    }

    /**
     * Get the application that owns the invoice.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the user that owns the invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the creator of the invoice.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater of the invoice.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $date = date('Ymd');
        
        // Get the last invoice number
        $lastInvoice = self::where('invoice_no', 'like', $prefix . $date . '%')
            ->orderBy('invoice_no', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $date . '-' . $newNumber;
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    /**
     * Check if invoice is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->isPending() && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(
        string $paymentMethod = null, 
        string $transactionId = null, 
        array $paymentDetails = null
    ): bool {
        $this->payment_status = self::STATUS_PAID;
        $this->paid_at = now();
        
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        
        if ($paymentDetails) {
            $this->payment_details = $paymentDetails;
        }
        
        if (auth()->check()) {
            $this->updated_by = auth()->id();
        }
        
        try {
            return $this->save();
        } catch (\Exception $e) {
            Log::error('Error marking invoice as paid', [
                'invoice_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Mark invoice as failed.
     */
    public function markAsFailed(string $failureReason = null): bool
    {
        $this->payment_status = self::STATUS_FAILED;
        
        if ($failureReason) {
            $currentDetails = $this->payment_details ?? [];
            $currentDetails['failure_reason'] = $failureReason;
            $currentDetails['failed_at'] = now()->toDateTimeString();
            $this->payment_details = $currentDetails;
        }
        
        if (auth()->check()) {
            $this->updated_by = auth()->id();
        }
        
        try {
            return $this->save();
        } catch (\Exception $e) {
            Log::error('Error marking invoice as failed', [
                'invoice_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get payment method display name.
     */
    public function getPaymentMethodName(): string
    {
        $methods = [
            self::METHOD_BKASH => 'bKash',
            self::METHOD_ROCKET => 'Rocket',
            self::METHOD_NAGAD => 'Nagad',
            self::METHOD_CASH => 'Cash',
            self::METHOD_BANK => 'Bank Transfer',
            self::METHOD_OTHER => 'Other',
        ];
        
        return $methods[$this->payment_method] ?? ucfirst($this->payment_method ?? 'Unknown');
    }

    /**
     * Get payment status display name.
     */
    public function getPaymentStatusName(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
        ];
        
        return $statuses[$this->payment_status] ?? ucfirst($this->payment_status ?? 'Unknown');
    }

    /**
     * Get payment status badge class.
     */
    public function getPaymentStatusBadge(): string
    {
        $classes = [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PAID => 'bg-green-100 text-green-800',
            self::STATUS_FAILED => 'bg-red-100 text-red-800',
            self::STATUS_CANCELLED => 'bg-gray-100 text-gray-800',
            self::STATUS_REFUNDED => 'bg-blue-100 text-blue-800',
        ];
        
        return $classes[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Scope a query to only include paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    /**
     * Scope a query to only include pending invoices.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include failed invoices.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', self::STATUS_FAILED);
    }

    /**
     * Scope a query to only include overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING)
            ->whereDate('due_date', '<', now());
    }

    /**
     * Scope a query to only include invoices for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include invoices for a specific application.
     */
    public function scopeForApplication($query, $applicationId)
    {
        return $query->where('application_id', $applicationId);
    }

    /**
     * Scope a query to only include invoices with a specific payment method.
     */
    public function scopeWithPaymentMethod($query, $paymentMethod)
    {
        return $query->where('payment_method', $paymentMethod);
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmount(): string
    {
        return '৳' . number_format($this->amount, 2);
    }

    /**
     * Get payment details as array.
     */
    public function getPaymentDetailsArray(): array
    {
        if (is_array($this->payment_details)) {
            return $this->payment_details;
        }
        
        if (is_string($this->payment_details)) {
            return json_decode($this->payment_details, true) ?? [];
        }
        
        return [];
    }

    /**
     * Add payment detail.
     */
    public function addPaymentDetail(string $key, $value): bool
    {
        $details = $this->getPaymentDetailsArray();
        $details[$key] = $value;
        $this->payment_details = $details;
        
        return $this->save();
    }

    /**
     * Get payment detail.
     */
    public function getPaymentDetail(string $key, $default = null)
    {
        $details = $this->getPaymentDetailsArray();
        return $details[$key] ?? $default;
    }

    /**
     * Check if invoice can be paid.
     */
    public function canBePaid(): bool
    {
        return $this->isPending() && !$this->isOverdue();
    }

    /**
     * Get days overdue.
     */
    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return $this->due_date->diffInDays(now());
    }

    /**
     * Update related application payment status when invoice is paid.
     */
    public function updateApplicationPaymentStatus(): bool
    {
        if ($this->application && $this->isPaid()) {
            try {
                $this->application->payment_status = 'paid';
                $this->application->save();
                
                Log::info('Application payment status updated from invoice', [
                    'invoice_id' => $this->id,
                    'application_id' => $this->application_id,
                    'updated_by' => auth()->id() ?? 'system'
                ]);
                
                return true;
            } catch (\Exception $e) {
                Log::error('Error updating application payment status', [
                    'invoice_id' => $this->id,
                    'application_id' => $this->application_id,
                    'error' => $e->getMessage()
                ]);
                return false;
            }
        }
        
        return false;
    }

    /**
     * Get invoice summary for display.
     */
    public function getSummary(): array
    {
        return [
            'invoice_no' => $this->invoice_no,
            'amount' => $this->getFormattedAmount(),
            'payment_status' => $this->getPaymentStatusName(),
            'payment_method' => $this->getPaymentMethodName(),
            'created_at' => $this->created_at->format('d M, Y'),
            'due_date' => $this->due_date ? $this->due_date->format('d M, Y') : 'N/A',
            'paid_at' => $this->paid_at ? $this->paid_at->format('d M, Y H:i') : 'N/A',
            'is_overdue' => $this->isOverdue(),
            'days_overdue' => $this->getDaysOverdue(),
        ];
    }

    /**
     * Accessor for compatibility with old code
     * যখন $invoice->status ব্যবহার করা হবে, তখন $invoice->payment_status রিটার্ন করবে
     */
    public function __get($key)
    {
        if ($key === 'status') {
            return $this->payment_status;
        }
        
        return parent::__get($key);
    }

    /**
     * Mutator for compatibility with old code
     * যখন $invoice->status = 'paid' সেট করা হবে, তখন $invoice->payment_status সেট হবে
     */
    public function __set($key, $value)
    {
        if ($key === 'status') {
            $this->attributes['payment_status'] = $value;
            return;
        }
        
        parent::__set($key, $value);
    }
}