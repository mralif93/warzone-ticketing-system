# Maintenance Mode System Guide

## Overview
The Warzone Ticketing System includes a comprehensive maintenance mode system that allows you to control site access using environment variables. This system provides a professional maintenance page and flexible IP whitelisting.

## Features

### ✅ **Environment Variable Control**
- Control maintenance mode via `.env` file
- Custom maintenance messages
- IP whitelisting with CIDR support
- Configurable retry-after headers

### ✅ **Professional Maintenance Page**
- Modern, responsive design
- Animated progress indicators
- Real-time status updates
- Contact information and support links
- Auto-refresh functionality

### ✅ **Flexible Access Control**
- IP-based whitelisting
- Bypass parameter for testing
- Admin access during maintenance
- Secure environment variable management

## Usage

### 1. **Basic Commands**

```bash
# Enable maintenance mode
php artisan maintenance on

# Disable maintenance mode
php artisan maintenance off

# Check current status
php artisan maintenance status
```

### 2. **Advanced Configuration**

```bash
# Enable with custom message
php artisan maintenance on --message="Scheduled system upgrade in progress"

# Enable with specific allowed IPs
php artisan maintenance on --ips="192.168.1.0/24,10.0.0.1,127.0.0.1"

# Enable with both custom message and IPs
php artisan maintenance on --message="Database migration in progress" --ips="192.168.1.0/24"
```

### 3. **Environment Variables**

Add these to your `.env` file:

```env
# Maintenance Mode Configuration
MAINTENANCE_MODE=false
MAINTENANCE_MESSAGE="We're currently performing scheduled maintenance. We'll be back online shortly!"
MAINTENANCE_ALLOWED_IPS="127.0.0.1,::1"
MAINTENANCE_RETRY_AFTER=3600
```

### 4. **Testing Maintenance Mode**

```bash
# Test maintenance page (bypass IP restrictions)
curl http://127.0.0.1:9000?bypass_maintenance=1

# Test normal access (should show maintenance page)
curl http://127.0.0.1:9000
```

## Configuration Options

### **MAINTENANCE_MODE**
- `true` - Enable maintenance mode
- `false` - Disable maintenance mode (normal operation)

### **MAINTENANCE_MESSAGE**
- Custom message displayed on maintenance page
- Supports HTML and special characters
- Default: "We're currently performing scheduled maintenance. We'll be back online shortly!"

### **MAINTENANCE_ALLOWED_IPS**
- Comma-separated list of allowed IP addresses
- Supports CIDR notation (e.g., `192.168.1.0/24`)
- Supports IPv4 and IPv6
- Default: `127.0.0.1,::1` (localhost only)

### **MAINTENANCE_RETRY_AFTER**
- HTTP Retry-After header value in seconds
- Tells browsers when to retry the request
- Default: `3600` (1 hour)

## IP Whitelisting Examples

```env
# Allow only localhost
MAINTENANCE_ALLOWED_IPS="127.0.0.1,::1"

# Allow local network
MAINTENANCE_ALLOWED_IPS="192.168.1.0/24,10.0.0.0/8"

# Allow specific IPs and ranges
MAINTENANCE_ALLOWED_IPS="192.168.1.100,192.168.1.0/24,10.0.0.1"

# Allow all IPs (not recommended for production)
MAINTENANCE_ALLOWED_IPS="*"
```

## Maintenance Page Features

### **Professional Design**
- Modern glass-morphism effects
- Smooth animations and transitions
- Responsive design for all devices
- Brand-consistent styling

### **User Experience**
- Clear status information
- Estimated completion time
- Progress indicators
- Contact information
- Auto-refresh functionality

### **Technical Features**
- Proper HTTP 503 status code
- Cache control headers
- Retry-after headers
- SEO-friendly (noindex, nofollow)

## Troubleshooting

### **Maintenance Mode Not Working**
1. Check if `MAINTENANCE_MODE=true` in `.env`
2. Clear config cache: `php artisan config:clear`
3. Verify middleware is registered in `Kernel.php`
4. Check if your IP is in the allowed list

### **Can't Access Site During Maintenance**
1. Add your IP to `MAINTENANCE_ALLOWED_IPS`
2. Use bypass parameter: `?bypass_maintenance=1`
3. Temporarily disable: `php artisan maintenance off`

### **Maintenance Page Not Displaying**
1. Check if `/maintenance` route is accessible
2. Verify `MaintenanceController` is working
3. Check browser console for JavaScript errors
4. Ensure `maintenance.blade.php` exists

## Security Considerations

- **IP Whitelisting**: Always use specific IPs, avoid wildcards in production
- **Environment Variables**: Keep `.env` file secure and not in version control
- **Bypass Parameter**: Only use for testing, not in production
- **Admin Access**: Ensure administrators can access during maintenance

## Best Practices

1. **Plan Maintenance Windows**: Schedule during low-traffic periods
2. **Communicate in Advance**: Notify users before maintenance
3. **Test Thoroughly**: Always test maintenance mode before going live
4. **Monitor Status**: Use `php artisan maintenance status` to check current state
5. **Quick Recovery**: Keep maintenance commands handy for quick enable/disable

## API Endpoints

### **GET /maintenance**
- Shows the maintenance page
- Returns HTTP 503 status
- Includes proper headers

### **POST /maintenance/toggle**
- Toggles maintenance mode
- Requires authentication in production
- Returns JSON response

## Support

For issues or questions about the maintenance system:
- Check the logs: `storage/logs/laravel.log`
- Run diagnostics: `php artisan maintenance status`
- Contact support: support@warzonechampionship.com
