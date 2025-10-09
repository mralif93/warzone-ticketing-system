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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'stripe_response' => 'array',
        'processed_at' => 'datetime',
    ];

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
        return $this->status === 'Succeeded';
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'Failed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'Refunded';
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
}