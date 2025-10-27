<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class EnvMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check maintenance mode from both .env and database settings
        $envMaintenanceMode = config('app.maintenance_mode', false);
        
        // Get DB maintenance mode - it's stored as boolean type but returns true/false
        $dbMaintenanceModeValue = Setting::get('maintenance_mode', false);
        
        // Convert various representations to boolean
        // DB might return '1', '0', true, false, or "true", "false"
        $dbMaintenanceMode = filter_var($dbMaintenanceModeValue, FILTER_VALIDATE_BOOLEAN);
        
        // Maintenance mode is active if either env or db says it is
        $isMaintenanceModeActive = $envMaintenanceMode || $dbMaintenanceMode;
        
        // Debug logging (remove in production)
        Log::debug('Maintenance mode check', [
            'env_mode' => $envMaintenanceMode ? 'true' : 'false',
            'db_value' => $dbMaintenanceModeValue,
            'db_mode' => $dbMaintenanceMode ? 'true' : 'false',
            'is_active' => $isMaintenanceModeActive ? 'true' : 'false',
        ]);
        
        if ($isMaintenanceModeActive) {
            $isAllowed = false;
            
            Log::debug('Maintenance mode is active, checking access', [
                'authenticated' => Auth::check(),
                'user_role' => Auth::check() ? Auth::user()->role : 'guest',
            ]);
            
            // Always allow login page so admins can authenticate
            if ($request->is('login') || $request->is('login/*')) {
                Log::debug('Allowing login page access during maintenance');
                return $next($request);
            }
            
            // Allow authenticated administrators to bypass maintenance mode
            if (Auth::check()) {
                $user = Auth::user();
                Log::debug('User authenticated during maintenance', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'is_administrator' => $user->hasRole('administrator') ? 'true' : 'false',
                ]);
                
                if ($user->hasRole('administrator')) {
                    $isAllowed = true;
                    Log::debug('Admin bypass maintenance mode');
                }
            }
            
            // Only check IP whitelist if admin check failed
            if (!$isAllowed) {
                // Get allowed IPs from environment
                $allowedIps = config('app.maintenance_allowed_ips', '');
                
                Log::debug('Non-admin access check', [
                    'allowed_ips_config' => $allowedIps,
                    'client_ip' => $request->ip(),
                ]);
                
                // If no allowed IPs configured, block everyone except admins
                if (empty($allowedIps)) {
                    $isAllowed = false;
                    Log::debug('No allowed IPs configured, blocking access');
                } else {
                    $allowedIpsArray = array_map('trim', explode(',', $allowedIps));
                    
                    // Check if current IP is allowed
                    $clientIp = $request->ip();
                    $isAllowed = in_array($clientIp, $allowedIpsArray) || 
                                in_array('*', $allowedIpsArray) ||
                                $this->isIpInRange($clientIp, $allowedIpsArray);
                    
                    Log::debug('IP check result', [
                        'client_ip' => $clientIp,
                        'allowed_ips' => $allowedIpsArray,
                        'is_allowed' => $isAllowed ? 'true' : 'false',
                    ]);
                }
            }
            
            // Allow bypass for testing with ?bypass_maintenance=1 parameter
            if ($request->has('bypass_maintenance') && $request->get('bypass_maintenance') === '1') {
                $isAllowed = true;
                Log::debug('Bypass maintenance mode via parameter');
            }
            
            // If not allowed, show maintenance page
            if (!$isAllowed) {
                // Check if this is already a maintenance page request to avoid redirect loops
                if ($request->is('maintenance') || $request->is('maintenance/*')) {
                    Log::debug('Allowing maintenance page request');
                    return $next($request);
                }
                
                Log::debug('Redirecting to maintenance page', [
                    'client_ip' => $request->ip(),
                    'user' => Auth::check() ? Auth::user()->email : 'guest',
                    'path' => $request->path(),
                    'isAllowed' => $isAllowed,
                ]);
                
                // Redirect to maintenance page
                return redirect()->route('maintenance');
            }
        }

        return $next($request);
    }
    
    /**
     * Check if IP is in any of the allowed IP ranges
     */
    private function isIpInRange(string $ip, array $allowedIps): bool
    {
        foreach ($allowedIps as $allowedIp) {
            // Check for CIDR notation (e.g., 192.168.1.0/24)
            if (strpos($allowedIp, '/') !== false) {
                if ($this->ipInCidr($ip, $allowedIp)) {
                    return true;
                }
            }
            // Check for exact match
            elseif ($ip === $allowedIp) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if IP is within CIDR range
     */
    private function ipInCidr(string $ip, string $cidr): bool
    {
        list($subnet, $mask) = explode('/', $cidr);
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            $maskLong = -1 << (32 - $mask);
            
            return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
        }
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // For IPv6, we'll do a simple string comparison for now
            // In production, you might want to use a more robust IPv6 CIDR library
            return strpos($ip, $subnet) === 0;
        }
        
        return false;
    }
}
