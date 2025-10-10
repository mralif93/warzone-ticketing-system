<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'base_price',
        'category',
        'color',
        'icon',
        'sort_order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get seats that belong to this price zone
     */
    public function seats()
    {
        return $this->hasMany(Seat::class, 'price_zone', 'name');
    }

    /**
     * Get tickets for this price zone
     */
    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Seat::class, 'price_zone', 'seat_id', 'name', 'id');
    }

    /**
     * Scope for active price zones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'RM' . number_format($this->base_price, 0);
    }

    /**
     * Get category badge color
     */
    public function getCategoryBadgeColorAttribute()
    {
        return match($this->category) {
            'Premium' => 'amber',
            'Level' => 'blue',
            'Standing' => 'green',
            default => 'gray'
        };
    }

    /**
     * Check if price zone has seats
     */
    public function hasSeats()
    {
        return $this->seats()->exists();
    }

    /**
     * Get seat count for this price zone
     */
    public function getSeatCountAttribute()
    {
        return $this->seats()->count();
    }

    /**
     * Get available seat count for an event
     */
    public function getAvailableSeatsForEvent(Event $event)
    {
        return $this->seats()
            ->whereNotIn('id', function($query) use ($event) {
                $query->select('seat_id')
                    ->from('tickets')
                    ->where('event_id', $event->id)
                    ->whereIn('status', ['Held', 'Sold']);
            })
            ->count();
    }
}
