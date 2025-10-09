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
        'seat_id',
        'qrcode',
        'status',
        'is_scanned',
        'scanned_at',
        'scanned_by',
        'gate_location',
        'price_paid',
    ];

    protected $casts = [
        'is_scanned' => 'boolean',
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
     * Get the seat for this ticket
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
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
        return $this->status === 'Scanned' || $this->is_scanned;
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
    public function markAsScanned(string $scannedBy, string $gateLocation): void
    {
        $this->update([
            'status' => 'Scanned',
            'is_scanned' => true,
            'scanned_at' => now(),
            'scanned_by' => $scannedBy,
            'gate_location' => $gateLocation,
        ]);
    }

    /**
     * Get seat identifier
     */
    public function getSeatIdentifierAttribute(): string
    {
        return $this->seat ? $this->seat->seat_identifier : 'Unknown';
    }

    /**
     * Get ticket display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "Ticket #{$this->id} - {$this->seat_identifier}";
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
}