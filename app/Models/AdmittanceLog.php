<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmittanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_ticket_id',
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
     * Set scan_result to lowercase when saving
     */
    public function setScanResultAttribute($value)
    {
        $this->attributes['scan_result'] = strtolower($value);
    }

    /**
     * Get the purchase ticket for this log entry
     */
    public function purchaseTicket()
    {
        return $this->belongsTo(PurchaseTicket::class, 'purchase_ticket_id');
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
        return strtolower($this->scan_result) === 'success';
    }

    /**
     * Check if scan was a duplicate
     */
    public function isDuplicate(): bool
    {
        return strtolower($this->scan_result) === 'duplicate';
    }

    /**
     * Check if scan was wrong gate
     */
    public function isWrongGate(): bool
    {
        return strtolower($this->scan_result) === 'wrong_gate';
    }

    /**
     * Check if scan was invalid
     */
    public function isInvalid(): bool
    {
        return strtolower($this->scan_result) === 'invalid';
    }

    /**
     * Check if scan was expired
     */
    public function isExpired(): bool
    {
        return strtolower($this->scan_result) === 'expired';
    }

    /**
     * Get scan result color for UI
     */
    public function getScanResultColorAttribute(): string
    {
        return match(strtolower($this->scan_result)) {
            'success' => 'green',
            'duplicate' => 'red',
            'wrong_gate' => 'yellow',
            'wrong_event' => 'yellow',
            'invalid' => 'red',
            'expired' => 'red',
            'error' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope for successful scans
     */
    public function scopeSuccessful($query)
    {
        return $query->where('scan_result', 'success');
    }

    /**
     * Scope for failed scans
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('scan_result', ['duplicate', 'invalid', 'expired', 'wrong_gate', 'wrong_event']);
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