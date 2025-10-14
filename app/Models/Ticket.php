<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'event_id',
        'zone',
        'qrcode',
        'status',
        'scanned_at',
        'price_paid',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'price_paid' => 'decimal:2',
    ];

    /**
     * Get the order for this ticket
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the event for this ticket
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the admittance logs for this ticket
     */
    public function admittanceLogs()
    {
        return $this->hasMany(AdmittanceLog::class);
    }

    /**
     * Generate unique QR code
     */
    public static function generateQRCode(): string
    {
        do {
            $qrcode = 'WZ' . Str::random(32);
        } while (self::where('qrcode', $qrcode)->exists());

        return $qrcode;
    }

    /**
     * Check if ticket is sold
     */
    public function isSold(): bool
    {
        return $this->status === 'Sold';
    }

    /**
     * Check if ticket is held
     */
    public function isHeld(): bool
    {
        return $this->status === 'Held';
    }

    /**
     * Check if ticket is scanned
     */
    public function isScanned(): bool
    {
        return $this->status === 'Scanned';
    }

    /**
     * Check if ticket is invalid
     */
    public function isInvalid(): bool
    {
        return $this->status === 'Invalid';
    }

    /**
     * Check if ticket is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'Refunded';
    }

    /**
     * Mark ticket as scanned
     */
    public function markAsScanned(): void
    {
        $this->update([
            'status' => 'Scanned',
            'scanned_at' => now(),
        ]);
    }

    /**
     * Get ticket identifier
     */
    public function getTicketIdentifierAttribute(): string
    {
        return "TKT-{$this->id}";
    }

    /**
     * Get ticket display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "Ticket #{$this->id}";
    }

    /**
     * Scope for sold tickets
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'Sold');
    }

    /**
     * Scope for held tickets
     */
    public function scopeHeld($query)
    {
        return $query->where('status', 'Held');
    }

    /**
     * Scope for scanned tickets
     */
    public function scopeScanned($query)
    {
        return $query->where('status', 'Scanned');
    }

    /**
     * Scope for event
     */
    public function scopeForEvent($query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    /**
     * Scope for zone
     */
    public function scopeForZone($query, string $zone)
    {
        return $query->where('zone', $zone);
    }

    /**
     * Scope for available tickets (not sold or held)
     */
    public function scopeAvailable($query)
    {
        return $query->whereNotIn('status', ['Sold', 'Held']);
    }

    /**
     * Get zone price based on zone
     */
    public function getZonePrice(): float
    {
        $zonePrices = [
            'Warzone Exclusive' => 350.00,
            'Warzone VIP' => 250.00,
            'Warzone Grandstand' => 220.00,
            'Warzone Premium Ringside' => 199.00,
            'Level 1 Zone A/B/C/D' => 129.00,
            'Level 2 Zone A/B/C/D' => 89.00,
            'Standing Zone A/B' => 49.00,
        ];

        return $zonePrices[$this->zone] ?? 49.00;
    }

    /**
     * Check if ticket is for a specific zone
     */
    public function isForZone(string $zone): bool
    {
        return $this->zone === $zone;
    }
}