<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section',
        'row',
        'number',
        'price_zone',
        'base_price',
        'is_accessible',
        'seat_type',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_accessible' => 'boolean',
    ];

    /**
     * Get the tickets for this seat
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get seat identifier (e.g., "A-1-15")
     */
    public function getSeatIdentifierAttribute(): string
    {
        return "{$this->section}-{$this->row}-{$this->number}";
    }

    /**
     * Check if seat is available for a specific event
     */
    public function isAvailableForEvent(Event $event): bool
    {
        return !$this->tickets()
            ->where('event_id', $event->id)
            ->whereIn('status', ['Held', 'Sold'])
            ->exists();
    }

    /**
     * Get current ticket for a specific event
     */
    public function getTicketForEvent(Event $event): ?Ticket
    {
        return $this->tickets()
            ->where('event_id', $event->id)
            ->whereIn('status', ['Held', 'Sold'])
            ->first();
    }

    /**
     * Scope for available seats
     */
    public function scopeAvailable($query, Event $event)
    {
        return $query->whereNotIn('id', function($subQuery) use ($event) {
            $subQuery->select('seat_id')
                    ->from('tickets')
                    ->where('event_id', $event->id)
                    ->whereIn('status', ['Held', 'Sold']);
        });
    }

    /**
     * Scope for price zone
     */
    public function scopeByPriceZone($query, string $priceZone)
    {
        return $query->where('price_zone', $priceZone);
    }

    /**
     * Scope for accessible seats
     */
    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }
}