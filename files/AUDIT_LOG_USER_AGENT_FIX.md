# Audit Log User Agent Field Length Fix

## Issue Description

**Error:** `SQLSTATE[22001]: String data, right truncated: 7 ERROR: value too long for type character...`

**Location:** `app/Http/Controllers/Auth/AuthController.php:88` (login method)

**Root Cause:**
The `user_agent` field in the `audit_logs` table was defined as `string()` (VARCHAR(255)) in the migration. Mobile browsers, especially when embedded in social media apps like Instagram, Facebook, or Threads, can send user agent strings that exceed 255 characters. This causes a database truncation error when trying to insert audit log entries.

**Example of Long User Agent:**
Mobile in-app browsers often include multiple identifiers, device information, and app-specific metadata, resulting in user agent strings that can be 300-500+ characters long.

## Solution

### Migration Created
- **File:** `database/migrations/2025_11_13_142255_fix_audit_logs_user_agent_field_length.php`
- **Changes:**
  1. Changed `user_agent` column from `string()` (VARCHAR 255) to `text()` to accommodate unlimited length
  2. Updated `ip_address` to explicitly specify length of 45 characters (for IPv6 support)

### Impact
- ✅ Fixes login failures caused by long user agent strings
- ✅ Fixes all audit log creation operations (login, logout, registration, etc.)
- ✅ No code changes required - all existing `AuditLog::create()` calls will work automatically
- ✅ Backward compatible - existing data remains intact

## Testing

After running the migration, test the following:
1. Login from mobile device (especially Instagram/Facebook in-app browser)
2. Registration from mobile device
3. Any admin operations that create audit logs

## Migration Command

```bash
php artisan migrate
```

## Rollback (if needed)

```bash
php artisan migrate:rollback --step=1
```

Note: The rollback will revert `user_agent` to `string(500)` for safety, but this may still cause issues with very long user agents.

