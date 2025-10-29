<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'date_time',
        'start_date',
        'end_date',
        'status',
        'max_tickets_per_order',
        'total_seats',
        'description',
        'venue',
        'combo_discount_percentage',
        'combo_discount_enabled',
        'default',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_seats' => 'integer',
        'combo_discount_percentage' => 'decimal:2',
        'combo_discount_enabled' => 'boolean',
        'default' => 'boolean',
    ];

    /**
     * Get the purchase tickets for this event
     */
    public function purchaseTickets()
    {
        return $this->hasMany(PurchaseTicket::class);
    }

    /**
     * Get the customer tickets for this event - legacy
     * @deprecated Use purchaseTickets() instead
     */
    public function customerTickets()
    {
        return $this->purchaseTickets();
    }

    /**
     * Get the ticket types for this event
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the ticket types for this event
     */
    public function ticketTypes()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the zones for this event - legacy
     * @deprecated Use ticketTypes() instead
     */
    public function zones()
    {
        return $this->ticketTypes();
    }

    /**
     * Check if event is on sale
     */
    public function isOnSale(): bool
    {
        return $this->status === 'on_sale';
    }

    /**
     * Check if event is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->status === 'sold_out';
    }

    /**
     * Get total tickets sold for this event
     */
    public function getTicketsSoldCount(): int
    {
        return $this->purchaseTickets()->whereIn('status', ['sold', 'active', 'scanned'])->count();
    }

    /**
     * Get total capacity from database
     */
    public function getTotalCapacity(): int
    {
        return $this->total_seats ?? 7000; // Default to 7000 if not set
    }

    /**
     * Get remaining tickets
     */
    public function getRemainingTicketsCount(): int
    {
        return $this->getTotalCapacity() - $this->getTicketsSoldCount();
    }

    /**
     * Check if event has available seats
     */
    public function hasAvailableSeats(): bool
    {
        return $this->getRemainingTicketsCount() > 0;
    }

    /**
     * Get tickets sold percentage
     */
    public function getSoldPercentage(): float
    {
        $totalCapacity = $this->getTotalCapacity();
        if ($totalCapacity === 0) {
            return 0;
        }
        
        return round(($this->getTicketsSoldCount() / $totalCapacity) * 100, 2);
    }

    /**
     * Get tickets available percentage
     */
    public function getAvailablePercentage(): float
    {
        $totalCapacity = $this->getTotalCapacity();
        if ($totalCapacity === 0) {
            return 0;
        }
        
        return round(($this->getRemainingTicketsCount() / $totalCapacity) * 100, 2);
    }

    /**
     * Check if event is multi-day
     */
    public function isMultiDay(): bool
    {
        return $this->start_date && $this->end_date && 
               $this->start_date->format('Y-m-d') !== $this->end_date->format('Y-m-d');
    }

    /**
     * Get event duration in days
     */
    public function getDurationInDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 1; // Single day event
        }
        
        return (int) $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRange(): string
    {
        // If we have start_date and end_date, use them
        if ($this->start_date && $this->end_date) {
            if ($this->isMultiDay()) {
                return $this->start_date->format('M j, Y') . ' - ' . $this->end_date->format('M j, Y');
            } else {
                return $this->start_date->format('M j, Y \a\t g:i A') . ' - ' . $this->end_date->format('g:i A');
            }
        }
        
        // Fallback to date_time for backward compatibility
        return $this->date_time->format('M j, Y \a\t g:i A');
    }

    /**
     * Get primary date for display (backward compatibility)
     */
    public function getPrimaryDate(): \Carbon\Carbon
    {
        return $this->start_date ?? $this->date_time;
    }


    /**
     * Get tickets sold for a specific day
     */
    public function getTicketsSoldForDay($eventDay): int
    {
        return $this->purchaseTickets()
            ->where('event_day', $eventDay)
            ->whereIn('status', ['sold', 'active', 'scanned'])
            ->count();
    }

    /**
     * Get tickets available for a specific day
     */
    public function getTicketsAvailableForDay($eventDay): int
    {
        $sold = $this->getTicketsSoldForDay($eventDay);
        return $this->getTotalCapacity() - $sold;
    }

    /**
     * Check if a specific day has available tickets
     */
    public function hasAvailableTicketsForDay($eventDay): bool
    {
        return $this->getTicketsAvailableForDay($eventDay) > 0;
    }

    /**
     * Check if this is a 2-day event
     */
    public function isTwoDayEvent(): bool
    {
        return $this->isMultiDay() && $this->getDurationInDays() === 2;
    }

    /**
     * Get combo discount percentage
     */
    public function getComboDiscountPercentage(): float
    {
        return $this->combo_discount_enabled ? (float) $this->combo_discount_percentage : 0;
    }

    /**
     * Calculate combo discount amount
     */
    public function calculateComboDiscount($originalPrice): float
    {
        if (!$this->combo_discount_enabled || !$this->isTwoDayEvent()) {
            return 0;
        }
        
        return round($originalPrice * ($this->getComboDiscountPercentage() / 100), 2);
    }

    /**
     * Calculate combo price (2 tickets with discount)
     */
    public function calculateComboPrice($singleTicketPrice): array
    {
        $originalTotal = $singleTicketPrice * 2;
        $discountAmount = $this->calculateComboDiscount($originalTotal);
        $finalPrice = $originalTotal - $discountAmount;
        
        return [
            'original_price' => $originalTotal,
            'discount_percentage' => $this->getComboDiscountPercentage(),
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'savings' => $discountAmount,
        ];
    }

    /**
     * Calculate combo price for mixed ticket types
     */
    public function calculateMixedComboPrice($ticketPrices): array
    {
        if (!$this->combo_discount_enabled || !$this->isTwoDayEvent() || count($ticketPrices) !== 2) {
            return [
                'original_price' => array_sum($ticketPrices),
                'discount_percentage' => 0,
                'discount_amount' => 0,
                'final_price' => array_sum($ticketPrices),
                'savings' => 0,
            ];
        }

        $originalTotal = array_sum($ticketPrices);
        $discountAmount = $this->calculateComboDiscount($originalTotal);
        $finalPrice = $originalTotal - $discountAmount;
        
        return [
            'original_price' => $originalTotal,
            'discount_percentage' => $this->getComboDiscountPercentage(),
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
            'savings' => $discountAmount,
            'ticket_breakdown' => $this->calculateTicketBreakdown($ticketPrices, $discountAmount),
        ];
    }

    /**
     * Calculate individual ticket breakdown for mixed combo
     */
    private function calculateTicketBreakdown($ticketPrices, $totalDiscount): array
    {
        $totalOriginal = array_sum($ticketPrices);
        $breakdown = [];
        
        foreach ($ticketPrices as $index => $price) {
            $discountShare = ($price / $totalOriginal) * $totalDiscount;
            $breakdown[] = [
                'original_price' => $price,
                'discount_amount' => round($discountShare, 2),
                'final_price' => round($price - $discountShare, 2),
            ];
        }
        
        return $breakdown;
    }

    /**
     * Get available event days (max 2 days)
     */
    public function getEventDays(): array
    {
        if (!$this->isMultiDay()) {
            return [
                [
                    'date' => $this->date_time->toDateString(),
                    'day_name' => 'Event Day',
                    'display' => $this->date_time->format('M j, Y'),
                ]
            ];
        }

        $days = [];
        $current = $this->start_date->copy();
        $end = $this->end_date->copy();
        $dayNumber = 1;

        // Limit to maximum 2 days
        $maxDays = min(2, $this->getDurationInDays());
        
        while ($current->lte($end) && $dayNumber <= $maxDays) {
            $days[] = [
                'date' => $current->toDateString(),
                'day_name' => "Day {$dayNumber}",
                'display' => $current->format('M j, Y'),
            ];
            $current->addDay();
            $dayNumber++;
        }

        return $days;
    }

    /**
     * Check if this event is the default event
     */
    public function isDefault(): bool
    {
        return $this->default === true;
    }

    /**
     * Set this event as the default event
     */
    public function setAsDefault(): void
    {
        // First, unset any existing default event
        self::where('default', true)->update(['default' => false]);
        
        // Set this event as default
        $this->update(['default' => true]);
        
        // Verify only one default event exists
        $defaultCount = self::where('default', true)->count();
        if ($defaultCount > 1) {
            throw new \Exception('Multiple default events detected. This should not happen.');
        }
    }

    /**
     * Unset this event as the default event
     */
    public function unsetAsDefault(): void
    {
        $this->update(['default' => false]);
    }

    /**
     * Get the default event
     */
    public static function getDefault(): ?self
    {
        return self::where('default', true)->first();
    }

    /**
     * Scope for default events
     */
    public function scopeDefault($query)
    {
        return $query->where('default', true);
    }

    /**
     * Scope for non-default events
     */
    public function scopeNonDefault($query)
    {
        return $query->where('default', false);
    }

    /**
     * Ensure only one default event exists
     * This method should be called after any bulk operations
     */
    public static function ensureSingleDefault(): void
    {
        $defaultEvents = self::where('default', true)->get();
        
        if ($defaultEvents->count() > 1) {
            // Keep only the first one as default, unset the rest
            $keepAsDefault = $defaultEvents->first();
            self::where('default', true)
                ->where('id', '!=', $keepAsDefault->id)
                ->update(['default' => false]);
        }
    }

    /**
     * Get start time from date_time
     */
    public function getStartTimeAttribute()
    {
        return $this->date_time ? $this->date_time->format('g:i A') : null;
    }

    /**
     * Get end time (calculated from date_time with default duration of 4 hours)
     */
    public function getEndTimeAttribute()
    {
        if (!$this->date_time) {
            return null;
        }
        
        // Add 4 hours to date_time as default event duration
        return $this->date_time->copy()->addHours(4)->format('g:i A');
    }

    /**
     * Boot method to ensure single default event
     */
    protected static function boot()
    {
        parent::boot();

        // After any event is saved, ensure only one default exists
        static::saved(function ($event) {
            if ($event->default) {
                self::ensureSingleDefault();
            }
        });
    }
}