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
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_seats' => 'integer',
    ];

    /**
     * Get the tickets for this event
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Check if event is on sale
     */
    public function isOnSale(): bool
    {
        return $this->status === 'On Sale';
    }

    /**
     * Check if event is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->status === 'Sold Out';
    }

    /**
     * Get total tickets sold for this event
     */
    public function getTicketsSoldCount(): int
    {
        return $this->tickets()->where('status', 'Sold')->count();
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
}