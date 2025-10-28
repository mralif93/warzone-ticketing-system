<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stripe_charge_id',
        'stripe_payment_intent_id',
        'method',
        'amount',
        'currency',
        'status',
        'stripe_response',
        'failure_reason',
        'processed_at',
        'refund_amount',
        'refund_date',
        'refund_reason',
        'refund_method',
        'refund_reference',
        'transaction_id',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'stripe_response' => 'array',
        'processed_at' => 'datetime',
        'refund_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    /**
     * Automatically convert status to lowercase when setting
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ? strtolower($value) : null;
    }

    /**
     * Automatically get status in lowercase
     */
    public function getStatusAttribute($value)
    {
        return $value ? strtolower($value) : $value;
    }

    /**
     * Get the order for this payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment was successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'succeeded';
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Check if payment is via Stripe
     */
    public function isStripePayment(): bool
    {
        return $this->method === 'Credit Card' && !empty($this->stripe_charge_id);
    }

    /**
     * Check if payment is cash
     */
    public function isCashPayment(): bool
    {
        return $this->method === 'Cash';
    }

    /**
     * Check if payment is complimentary
     */
    public function isCompPayment(): bool
    {
        return $this->method === 'Comp';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * Check if payment has been refunded
     */
    public function hasRefund(): bool
    {
        return !is_null($this->refund_amount) && $this->refund_amount > 0;
    }

    /**
     * Check if payment is fully refunded
     */
    public function isFullyRefunded(): bool
    {
        return $this->hasRefund() && $this->refund_amount >= $this->amount;
    }

    /**
     * Check if payment is partially refunded
     */
    public function isPartiallyRefunded(): bool
    {
        return $this->hasRefund() && $this->refund_amount < $this->amount;
    }

    /**
     * Get remaining refundable amount
     */
    public function getRemainingRefundableAmount(): float
    {
        if (!$this->hasRefund()) {
            return $this->amount;
        }
        
        return max(0, $this->amount - $this->refund_amount);
    }

    /**
     * Process refund
     */
    public function processRefund(float $amount, string $reason, ?string $method = null, ?string $reference = null): bool
    {
        // Validate payment status
        if (!$this->isSuccessful()) {
            throw new \InvalidArgumentException('Only successful payments can be refunded');
        }

        // Validate refund amount
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Refund amount must be greater than 0');
        }

        if ($amount > $this->getRemainingRefundableAmount()) {
            throw new \InvalidArgumentException('Refund amount cannot exceed remaining refundable amount');
        }

        // Update payment with refund information
        $this->update([
            'refund_amount' => ($this->refund_amount ?? 0) + $amount,
            'refund_date' => now(),
            'refund_reason' => $reason,
            'refund_method' => $method ?? $this->method,
            'refund_reference' => $reference,
            'status' => $this->isFullyRefunded() ? 'refunded' : 'partially_refunded'
        ]);

        return true;
    }

    /**
     * Get refund status
     */
    public function getRefundStatus(): string
    {
        if (!$this->hasRefund()) {
            return 'No Refund';
        }

        if ($this->isFullyRefunded()) {
            return 'Fully Refunded';
        }

        if ($this->isPartiallyRefunded()) {
            return 'Partially Refunded';
        }

        return 'Unknown';
    }

    /**
     * Get formatted payment method name
     */
    public function getFormattedMethodAttribute(): string
    {
        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'online_banking' => 'Online Banking',
            'qr_code_ewallet' => 'QR Code / E-Wallet',
            'debit_credit_card' => 'Debit / Credit Card',
            'others' => 'Others',
        ];

        return $paymentMethods[$this->method] ?? ucwords(str_replace('_', ' ', $this->method));
    }
}