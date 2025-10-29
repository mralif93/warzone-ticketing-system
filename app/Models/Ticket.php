<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'total_seats',
        'available_seats',
        'sold_seats',
        'scanned_seats',
        'status',
        'description',
        'seating_image',
        'is_combo',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_combo' => 'boolean',
    ];

    /**
     * Get the event for this zone
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the purchase tickets for this ticket type
     */
    public function purchaseTickets()
    {
        return $this->hasMany(PurchaseTicket::class, 'ticket_type_id');
    }

    /**
     * Get the customer tickets for this zone - legacy
     * @deprecated Use purchaseTickets() instead
     */
    public function customerTickets()
    {
        return $this->purchaseTickets();
    }

    /**
     * Get the tickets for this zone - legacy
     * @deprecated Use purchaseTickets() instead
     */
    public function tickets()
    {
        return $this->purchaseTickets();
    }

    /**
     * Check if zone is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->available_seats <= 0;
    }

    /**
     * Check if zone is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get occupancy percentage
     */
    public function getOccupancyPercentage(): float
    {
        if ($this->total_seats == 0) {
            return 0;
        }
        
        // For combo tickets: sold_seats is PurchaseTicket count, need to divide by 2 for combo tickets
        if ($this->is_combo) {
            $comboTicketsSold = ceil($this->sold_seats / 2);
            return round(($comboTicketsSold / $this->total_seats) * 100, 2);
        }
        
        return round(($this->sold_seats / $this->total_seats) * 100, 2);
    }

    /**
     * Update sold seats count
     */
    public function updateSoldSeats(): void
    {
        // Count all sold tickets including sold, active, and scanned statuses
        // Scanned tickets should still count as sold
        // For combo tickets: 4 combo tickets = 8 PurchaseTicket records (4 Day 1 + 4 Day 2)
        // We count PurchaseTicket records directly
        $soldCount = $this->purchaseTickets()->whereIn('status', ['sold', 'active', 'scanned'])->count();
        $scannedCount = $this->purchaseTickets()->where('status', 'scanned')->count();
        
        // For combo tickets: total_seats represents combo ticket capacity
        // Each combo ticket sold creates 2 PurchaseTicket records (Day 1 + Day 2)
        // So we need to divide soldCount by 2 to get actual combo tickets sold
        if ($this->is_combo) {
            $comboTicketsSold = ceil($soldCount / 2); // Round up to handle partial counts
            $availableSeats = $this->total_seats - $comboTicketsSold;
            $this->update([
                'sold_seats' => $soldCount, // Keep PurchaseTicket count for display
                'scanned_seats' => $scannedCount,
                'available_seats' => max(0, $availableSeats), // Calculate based on combo ticket capacity
                'status' => $availableSeats <= 0 ? 'sold_out' : 'active',
            ]);
        } else {
            // Single-day tickets: count directly
            $this->update([
                'sold_seats' => $soldCount,
                'scanned_seats' => $scannedCount,
                'available_seats' => $this->total_seats - $soldCount,
                'status' => $this->total_seats - $soldCount <= 0 ? 'sold_out' : 'active',
            ]);
        }
    }

    /**
     * Scope for active zones
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for sold out zones
     */
    public function scopeSoldOut($query)
    {
        return $query->where('status', 'sold_out');
    }

    /**
     * Scope for event
     */
    public function scopeForEvent($query, Event $event)
    {
        return $query->where('event_id', $event->id);
    }

    /**
     * Check if this is a combo ticket type
     */
    public function isCombo(): bool
    {
        return $this->is_combo;
    }

    /**
     * Scope for combo ticket types
     */
    public function scopeCombo($query)
    {
        return $query->where('is_combo', true);
    }

    /**
     * Scope for non-combo ticket types
     */
    public function scopeNonCombo($query)
    {
        return $query->where('is_combo', false);
    }
}