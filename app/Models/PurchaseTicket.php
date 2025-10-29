<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PurchaseTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase';

    protected $fillable = [
        'order_id',
        'event_id',
        'event_day', // Date of the event day for multi-day events
        'event_day_name', // Name of the event day (e.g., "Day 1", "Friday")
        'ticket_type_id', // This will reference the tickets table (ticket types)
        'zone',
        'is_combo_purchase', // Is this part of a combo purchase
        'combo_group_id', // Groups combo tickets together
        'original_price', // Original price before discount
        'discount_amount', // Discount amount applied
        'qrcode',
        'status',
        'scanned_at',
        'cancelled_at',
        'cancellation_reason',
        'price_paid',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'price_paid' => 'decimal:2',
        'event_day' => 'date',
        'is_combo_purchase' => 'boolean',
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * Get the order for this customer ticket
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the ticket type for this purchase ticket
     */
    public function ticketType()
    {
        return $this->belongsTo(Ticket::class, 'ticket_type_id');
    }

    /**
     * Get the event for this customer ticket
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the admittance logs for this customer ticket
     */
    public function admittanceLogs()
    {
        return $this->hasMany(AdmittanceLog::class, 'purchase_ticket_id');
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
     * Check if customer ticket is sold
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if customer ticket is held
     */
    public function isHeld(): bool
    {
        return $this->status === 'held';
    }

    /**
     * Check if customer ticket is scanned
     */
    public function isScanned(): bool
    {
        return $this->status === 'scanned';
    }

    /**
     * Check if customer ticket is invalid
     */
    public function isInvalid(): bool
    {
        return $this->status === 'invalid';
    }

    /**
     * Check if customer ticket is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Mark customer ticket as scanned
     */
    public function markAsScanned(): void
    {
        $this->update([
            'status' => 'scanned',
            'scanned_at' => now(),
        ]);
    }

    /**
     * Get customer ticket identifier
     */
    public function getTicketIdentifierAttribute(): string
    {
        return "TKT-{$this->id}";
    }

    /**
     * Get customer ticket display name
     */
    public function getDisplayNameAttribute(): string
    {
        return "Customer Ticket #{$this->id}";
    }

    /**
     * Scope for sold customer tickets
     */
    public function scopeSold($query)
    {
        return $query->whereIn('status', ['sold', 'active', 'scanned']);
    }

    /**
     * Scope for held customer tickets
     */
    public function scopeHeld($query)
    {
        return $query->where('status', 'held');
    }

    /**
     * Scope for scanned customer tickets
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
     * Scope for available customer tickets (not sold or held)
     */
    public function scopeAvailable($query)
    {
        return $query->whereNotIn('status', ['sold', 'active', 'scanned', 'held']);
    }

    /**
     * Scope for specific event day
     */
    public function scopeForEventDay($query, $eventDay)
    {
        return $query->where('event_day', $eventDay);
    }

    /**
     * Scope for multi-day events (has event_day)
     */
    public function scopeMultiDay($query)
    {
        return $query->whereNotNull('event_day');
    }

    /**
     * Scope for single-day events (no event_day)
     */
    public function scopeSingleDay($query)
    {
        return $query->whereNull('event_day');
    }

    /**
     * Get formatted event day display
     */
    public function getEventDayDisplayAttribute(): string
    {
        if (!$this->event_day) {
            return 'Single Day Event';
        }
        
        $date = $this->event_day->format('M j, Y');
        return $this->event_day_name ? "{$this->event_day_name} ({$date})" : $date;
    }

    /**
     * Check if this is a multi-day event ticket
     */
    public function isMultiDayEvent(): bool
    {
        return !is_null($this->event_day);
    }

    /**
     * Check if this is a single-day event ticket
     */
    public function isSingleDayEvent(): bool
    {
        return is_null($this->event_day);
    }

    /**
     * Check if this is a combo purchase
     */
    public function isComboPurchase(): bool
    {
        return $this->is_combo_purchase;
    }

    /**
     * Get combo group tickets
     */
    public function getComboGroupTickets()
    {
        if (!$this->combo_group_id) {
            return collect([$this]);
        }
        
        return self::where('combo_group_id', $this->combo_group_id)->get();
    }

    /**
     * Get total savings from combo discount
     */
    public function getComboSavings(): float
    {
        if (!$this->is_combo_purchase) {
            return 0;
        }
        
        return $this->getComboGroupTickets()->sum('discount_amount');
    }

    /**
     * Get original total price before discount
     */
    public function getOriginalTotalPrice(): float
    {
        if (!$this->is_combo_purchase) {
            return $this->price_paid;
        }
        
        return $this->getComboGroupTickets()->sum('original_price');
    }

    /**
     * Scope for combo purchases
     */
    public function scopeCombo($query)
    {
        return $query->where('is_combo_purchase', true);
    }

    /**
     * Scope for non-combo purchases
     */
    public function scopeNonCombo($query)
    {
        return $query->where('is_combo_purchase', false);
    }

    /**
     * Scope for specific combo group
     */
    public function scopeComboGroup($query, $comboGroupId)
    {
        return $query->where('combo_group_id', $comboGroupId);
    }
}