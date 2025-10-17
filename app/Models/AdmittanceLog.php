<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmittanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'scan_time',
        'scan_result',
        'gate_id',
        'staff_user_id',
        'device_info',
        'ip_address',
        'notes',
    ];

    protected $casts = [
        'scan_time' => 'datetime',
    ];

    /**
     * Get the purchase ticket for this log entry
     */
    public function purchaseTicket()
    {
        return $this->belongsTo(PurchaseTicket::class, 'ticket_id');
    }

    /**
     * Get the ticket for this log entry - legacy
     * @deprecated Use purchaseTicket() instead
     */
    public function ticket()
    {
        return $this->purchaseTicket();
    }

    /**
     * Get the staff user who performed the scan
     */
    public function staffUser()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }

    /**
     * Check if scan was successful
     */
    public function isSuccessful(): bool
    {
        return $this->scan_result === 'SUCCESS';
    }

    /**
     * Check if scan was a duplicate
     */
    public function isDuplicate(): bool
    {
        return $this->scan_result === 'DUPLICATE';
    }

    /**
     * Check if scan was wrong gate
     */
    public function isWrongGate(): bool
    {
        return $this->scan_result === 'WRONG_GATE';
    }

    /**
     * Check if scan was invalid
     */
    public function isInvalid(): bool
    {
        return $this->scan_result === 'INVALID';
    }

    /**
     * Check if scan was expired
     */
    public function isExpired(): bool
    {
        return $this->scan_result === 'EXPIRED';
    }

    /**
     * Get scan result color for UI
     */
    public function getScanResultColorAttribute(): string
    {
        return match($this->scan_result) {
            'SUCCESS' => 'green',
            'DUPLICATE' => 'red',
            'WRONG_GATE' => 'yellow',
            'INVALID' => 'red',
            'EXPIRED' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope for successful scans
     */
    public function scopeSuccessful($query)
    {
        return $query->where('scan_result', 'SUCCESS');
    }

    /**
     * Scope for failed scans
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('scan_result', ['DUPLICATE', 'INVALID', 'EXPIRED']);
    }

    /**
     * Scope for gate
     */
    public function scopeForGate($query, string $gateId)
    {
        return $query->where('gate_id', $gateId);
    }

    /**
     * Scope for staff member
     */
    public function scopeForStaff($query, int $staffUserId)
    {
        return $query->where('staff_user_id', $staffUserId);
    }
}