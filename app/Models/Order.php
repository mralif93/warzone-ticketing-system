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
        'qrcode',
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

    /**
     * Generate QR code for order
     */
    public static function generateQRCode()
    {
        return 'ORD' . strtoupper(uniqid()) . rand(1000, 9999);
    }

    /**
     * Generate QR code if not exists
     */
    public function generateQRCodeIfNotExists()
    {
        if (!$this->qrcode) {
            $this->update(['qrcode' => self::generateQRCode()]);
        }
        return $this->qrcode;
    }

    /**
     * Get QR code data for display
     */
    public function getQRCodeData()
    {
        return [
            'order_number' => $this->order_number,
            'qrcode' => $this->qrcode,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'tickets_count' => $this->getTicketsCount(),
        ];
    }

    /**
     * Calculate subtotal from tickets
     */
    public function calculateSubtotalFromTickets()
    {
        return $this->tickets->sum('price_paid');
    }

    /**
     * Calculate service fee from tickets
     */
    public function calculateServiceFeeFromTickets()
    {
        $subtotal = $this->calculateSubtotalFromTickets();
        return $this->calculateServiceFee($subtotal);
    }

    /**
     * Calculate tax from tickets
     */
    public function calculateTaxFromTickets()
    {
        $subtotal = $this->calculateSubtotalFromTickets();
        $serviceFee = $this->calculateServiceFeeFromTickets();
        return $this->calculateTax($subtotal + $serviceFee);
    }

    /**
     * Calculate total amount from tickets
     */
    public function calculateTotalFromTickets()
    {
        $subtotal = $this->calculateSubtotalFromTickets();
        $serviceFee = $this->calculateServiceFeeFromTickets();
        $taxAmount = $this->calculateTaxFromTickets();
        return $subtotal + $serviceFee + $taxAmount;
    }

    /**
     * Recalculate order pricing from tickets
     */
    public function recalculatePricingFromTickets()
    {
        $subtotal = $this->calculateSubtotalFromTickets();
        $serviceFee = $this->calculateServiceFeeFromTickets();
        $taxAmount = $this->calculateTaxFromTickets();
        $totalAmount = $this->calculateTotalFromTickets();

        $this->update([
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
        ]);

        return $this;
    }

    /**
     * Calculate service fee (5% of subtotal)
     */
    private function calculateServiceFee($subtotal)
    {
        return round($subtotal * 0.05, 2);
    }

    /**
     * Calculate tax (6% of subtotal + service fee)
     */
    private function calculateTax($amount)
    {
        return round($amount * 0.06, 2);
    }

    /**
     * Boot method to set up model event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // When an order is loaded, ensure pricing is calculated from tickets
        static::retrieved(function ($order) {
            if ($order->tickets->count() > 0) {
                $calculatedTotal = $order->calculateTotalFromTickets();
                if (abs($order->total_amount - $calculatedTotal) > 0.01) {
                    // Pricing is out of sync, recalculate
                    $order->recalculatePricingFromTickets();
                }
            }
        });
    }
}