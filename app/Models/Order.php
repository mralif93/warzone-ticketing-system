<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_email',
        'order_number',
        'subtotal',
        'service_fee',
        'tax_amount',
        'total_amount',
        'status',
        'payment_method',
        'notes',
        'held_until',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'held_until' => 'datetime',
    ];

    /**
     * Get the user who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tickets for this order
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the payments for this order
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'WZ' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'Paid';
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if order is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'Refunded';
    }

    /**
     * Check if order is held and not expired
     */
    public function isHeld(): bool
    {
        return $this->status === 'Pending' && 
               $this->held_until && 
               $this->held_until->isFuture();
    }

    /**
     * Check if order hold has expired
     */
    public function isHoldExpired(): bool
    {
        return $this->status === 'Pending' && 
               $this->held_until && 
               $this->held_until->isPast();
    }

    /**
     * Get total tickets count
     */
    public function getTicketsCount(): int
    {
        return $this->tickets()->count();
    }

    /**
     * Get successful payment
     */
    public function getSuccessfulPayment()
    {
        return $this->payments()->where('status', 'Succeeded')->first();
    }
}