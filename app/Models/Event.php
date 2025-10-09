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
        'status',
        'max_tickets_per_order',
        'description',
        'venue',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    /**
     * Get the tickets for this event
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get available seats for this event
     */
    public function availableSeats()
    {
        return Seat::whereNotIn('id', function($query) {
            $query->select('seat_id')
                  ->from('tickets')
                  ->where('event_id', $this->id)
                  ->whereIn('status', ['Held', 'Sold']);
        });
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
     * Get total capacity (7000 seats)
     */
    public function getTotalCapacity(): int
    {
        return 7000;
    }

    /**
     * Get remaining tickets
     */
    public function getRemainingTicketsCount(): int
    {
        return $this->getTotalCapacity() - $this->getTicketsSoldCount();
    }
}