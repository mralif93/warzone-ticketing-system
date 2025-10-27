<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];


    /**
     * Get the user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create an audit log entry
     */
    public static function createLog(
        int $userId,
        string $action,
        string $tableName,
        int $recordId,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Sanitize array to remove sensitive fields
     */
    public static function sanitizeValues(array $values, string $tableName): array
    {
        $sensitiveFields = ['password', 'remember_token', 'api_token', 'api_secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($values[$field])) {
                $values[$field] = '***REDACTED***';
            }
        }
        
        return $values;
    }

    /**
     * Get changed values between old and new arrays
     */
    public static function getChangedValues(array $oldValues, array $newValues): array
    {
        $changedValues = [];
        
        foreach ($newValues as $key => $newValue) {
            // Skip sensitive fields from comparison
            if (in_array($key, ['password', 'remember_token', 'api_token', 'api_secret', 'created_at', 'updated_at'])) {
                continue;
            }
            
            $oldValue = $oldValues[$key] ?? null;
            
            if ($oldValue !== $newValue) {
                $changedValues[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }
        
        return $changedValues;
    }

    /**
     * Check if action is create
     */
    public function isCreate(): bool
    {
        return $this->action === 'CREATE';
    }

    /**
     * Check if action is update
     */
    public function isUpdate(): bool
    {
        return $this->action === 'UPDATE';
    }

    /**
     * Check if action is delete
     */
    public function isDelete(): bool
    {
        return $this->action === 'DELETE';
    }

    /**
     * Check if action is reissue
     */
    public function isReissue(): bool
    {
        return $this->action === 'REISSUE';
    }

    /**
     * Check if action is refund
     */
    public function isRefund(): bool
    {
        return $this->action === 'REFUND';
    }

    /**
     * Check if action is role change
     */
    public function isRoleChange(): bool
    {
        return $this->action === 'ROLE_CHANGE';
    }

    /**
     * Check if action is status change
     */
    public function isStatusChange(): bool
    {
        return $this->action === 'STATUS_CHANGE';
    }

    /**
     * Get formatted action description
     */
    public function getFormattedActionAttribute(): string
    {
        return match($this->action) {
            'CREATE' => 'Created',
            'UPDATE' => 'Updated',
            'DELETE' => 'Deleted',
            'REISSUE' => 'Reissued',
            'REFUND' => 'Refunded',
            'ROLE_CHANGE' => 'Changed Role',
            'STATUS_CHANGE' => 'Changed Status',
            default => ucfirst(strtolower($this->action)),
        };
    }

    /**
     * Scope for specific table
     */
    public function scopeForTable($query, string $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    /**
     * Scope for specific action
     */
    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}