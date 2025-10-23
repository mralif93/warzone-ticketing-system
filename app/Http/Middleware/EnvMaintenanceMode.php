<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        // Check if maintenance mode is enabled via environment variable
        if (config('app.maintenance_mode', false)) {
            // Get allowed IPs from environment
            $allowedIps = config('app.maintenance_allowed_ips', '127.0.0.1,::1');
            $allowedIpsArray = array_map('trim', explode(',', $allowedIps));
            
            // Check if current IP is allowed
            $clientIp = $request->ip();
            $isAllowed = in_array($clientIp, $allowedIpsArray) || 
                        in_array('*', $allowedIpsArray) ||
                        $this->isIpInRange($clientIp, $allowedIpsArray);
            
            // Allow bypass for testing with ?bypass_maintenance=1 parameter
            if ($request->has('bypass_maintenance') && $request->get('bypass_maintenance') === '1') {
                $isAllowed = true;
            }
            
            // If not allowed, show maintenance page
            if (!$isAllowed) {
                // Check if this is already a maintenance page request to avoid redirect loops
                if ($request->is('maintenance') || $request->is('maintenance/*')) {
                    return $next($request);
                }
                
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
