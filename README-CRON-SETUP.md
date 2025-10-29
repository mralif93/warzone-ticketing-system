# Server Cron Job Setup - Warzone Ticketing System

This guide explains how to set up automatic pending order cancellation on your server.

## Quick Setup

### Option 1: Automated Script (Recommended)

1. Copy the setup script to your server:
   ```bash
   scp setup-server-cron.sh ubuntu@your-server-ip:/tmp/
   ```

2. SSH into your server:
   ```bash
   ssh ubuntu@your-server-ip
   ```

3. Move to your project directory and run the script:
   ```bash
   cd /var/www/html/warzone-ticketing-system
   bash /tmp/setup-server-cron.sh
   ```

The script will:
- ✅ Detect your project path
- ✅ Test the cancellation command
- ✅ Set up cron job for your user
- ✅ Optionally set up cron for web server user
- ✅ Check and start cron service if needed
- ✅ Show monitoring commands

### Option 2: Manual Setup

If you prefer manual setup, run these commands on your server:

```bash
# 1. Navigate to project directory
cd /var/www/html/warzone-ticketing-system

# 2. Test the command manually
php artisan orders:cancel-pending

# 3. Edit crontab
crontab -e

# 4. Add this line (replace path with your actual path):
*/5 * * * * cd /var/www/html/warzone-ticketing-system && php artisan orders:cancel-pending >> /dev/null 2>&1

# 5. Verify cron job is added
crontab -l

# 6. Check cron service status (Ubuntu/Debian)
sudo systemctl status cron

# 7. Start and enable cron service if not running
sudo systemctl start cron
sudo systemctl enable cron
```

## How It Works

- **Frequency:** Every 5 minutes (`*/5 * * * *`)
- **Command:** Cancels pending orders older than 15 minutes
- **Logs:** Output is suppressed to prevent email spam
- **Result:** Orders with status "pending" older than 15 minutes are automatically changed to "cancelled"

## Verification

After setup, verify the cron job is working:

### Check Cron Jobs
```bash
crontab -l
```

### Check Cron Logs
```bash
# Ubuntu/Debian
sudo tail -f /var/log/syslog | grep CRON

# CentOS/RHEL
sudo tail -f /var/log/cron
```

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Test Manually
```bash
cd /var/www/html/warzone-ticketing-system
php artisan orders:cancel-pending
```

## Monitoring

### Check if Cron is Running
```bash
# Ubuntu/Debian
sudo systemctl status cron

# CentOS/RHEL
sudo systemctl status crond
```

### View Cron Process
```bash
ps aux | grep cron
```

### Check Laravel Scheduler
```bash
php artisan schedule:list
```

## Troubleshooting

### Cron Job Not Running

1. **Check cron service status:**
   ```bash
   sudo systemctl status cron
   ```

2. **Check if cron is enabled:**
   ```bash
   sudo systemctl is-enabled cron
   ```

3. **Start and enable cron:**
   ```bash
   sudo systemctl start cron
   sudo systemctl enable cron
   ```

### Command Not Found

If you get "command not found" error:

1. **Use full PHP path:**
   ```bash
   which php
   # Example output: /usr/bin/php
   
   # Update cron job with full path:
   */5 * * * * cd /var/www/html/warzone-ticketing-system && /usr/bin/php artisan orders:cancel-pending >> /dev/null 2>&1
   ```

### Permission Issues

If you see permission errors:

1. **Check file permissions:**
   ```bash
   ls -la storage/logs/
   ```

2. **Fix permissions if needed:**
   ```bash
   chmod -R 775 storage/
   chown -R www-data:www-data storage/
   ```

### Alternative: Supervisor Setup

For more reliable execution, use Supervisor:

```bash
# Install supervisor
sudo apt install supervisor

# Create config file
sudo nano /etc/supervisor/conf.d/laravel-scheduler.conf
```

Add this content:
```ini
[program:laravel-scheduler]
command=php /var/www/html/warzone-ticketing-system/artisan schedule:work
directory=/var/www/html/warzone-ticketing-system
user=www-data
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/var/log/laravel-scheduler.log
```

Reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-scheduler
```

## What Gets Cancelled

The system automatically cancels:
- ✅ Orders with status "pending"
- ✅ Older than 15 minutes
- ✅ Sets status to "cancelled"
- ✅ Records cancellation reason: "Payment timeout - exceeded 15 minutes"
- ✅ Updates `cancelled_at` timestamp

## Production Recommendations

1. **Enable cron service:**
   ```bash
   sudo systemctl enable cron
   ```

2. **Monitor logs regularly:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Set up log rotation** to prevent disk space issues

4. **Use Supervisor** for more robust process management

5. **Set up alerts** for failed cron executions

## Support

If you encounter issues:
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Test command manually: `php artisan orders:cancel-pending`
3. Verify cron service is running: `sudo systemctl status cron`
4. Check cron logs: `sudo tail -f /var/log/syslog | grep CRON`

