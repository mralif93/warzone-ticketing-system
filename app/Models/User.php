<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'department',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'state',
        'postcode',
        'country',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the orders for this user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the tickets for this user (through orders)
     */
    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    /**
     * Get the admittance logs for this user (as staff)
     */
    public function admittanceLogs()
    {
        return $this->hasMany(AdmittanceLog::class, 'staff_user_id');
    }

    /**
     * Get the audit logs for this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'Administrator';
    }

    /**
     * Check if user is gate staff
     */
    public function isGateStaff()
    {
        return $this->role === 'Gate Staff';
    }

    /**
     * Check if user is counter staff
     */
    public function isCounterStaff()
    {
        return $this->role === 'Counter Staff';
    }

    /**
     * Check if user is support staff
     */
    public function isSupportStaff()
    {
        return $this->role === 'Support Staff';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer()
    {
        return $this->role === 'Customer';
    }

    /**
     * Check if user is staff (any staff role)
     */
    public function isStaff()
    {
        return in_array($this->role, ['Gate Staff', 'Counter Staff', 'Support Staff', 'Administrator']);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $address = [];
        
        if ($this->address_line_1) $address[] = $this->address_line_1;
        if ($this->address_line_2) $address[] = $this->address_line_2;
        if ($this->address_line_3) $address[] = $this->address_line_3;
        if ($this->city) $address[] = $this->city;
        if ($this->state) $address[] = $this->state;
        if ($this->postcode) $address[] = $this->postcode;
        if ($this->country) $address[] = $this->country;
        
        return implode(', ', $address);
    }
}
