<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class MaintenanceController extends Controller
{
    /**
     * Show the maintenance page
     */
    public function index()
    {
        $message = config('app.maintenance_message', 'We\'re currently performing scheduled maintenance. We\'ll be back online shortly!');
        $retryAfter = config('app.maintenance_retry_after', 3600);
        
        return response()->view('maintenance', [
            'message' => $message,
            'retryAfter' => $retryAfter
        ], 503)
        ->header('Retry-After', $retryAfter)
        ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
    }
    
    /**
     * Toggle maintenance mode via environment variable
     */
    public function toggle(Request $request)
    {
        // Only allow this in local environment or with proper authentication
        if (app()->environment('production') && !auth()->check()) {
            abort(403, 'Unauthorized');
        }
        
        $currentMode = config('app.maintenance_mode', false);
        $newMode = !$currentMode;
        
        // Update the .env file
        $this->updateEnvFile('MAINTENANCE_MODE', $newMode ? 'true' : 'false');
        
        // Clear config cache to reload the new value
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        return response()->json([
            'success' => true,
            'maintenance_mode' => $newMode,
            'message' => $newMode ? 'Maintenance mode enabled' : 'Maintenance mode disabled'
        ]);
    }
    
    /**
     * Update a value in the .env file
     */
    private function updateEnvFile(string $key, string $value): void
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            // Create .env file if it doesn't exist
            $envContent = "APP_NAME=\"Warzone Ticketing System\"\n";
            $envContent .= "APP_ENV=local\n";
            $envContent .= "APP_KEY=\n";
            $envContent .= "APP_DEBUG=true\n";
            $envContent .= "APP_URL=http://localhost\n\n";
            $envContent .= "# Maintenance Mode Configuration\n";
            $envContent .= "MAINTENANCE_MODE=false\n";
            $envContent .= "MAINTENANCE_MESSAGE=\"We're currently performing scheduled maintenance. We'll be back online shortly!\"\n";
            $envContent .= "MAINTENANCE_ALLOWED_IPS=\"127.0.0.1,::1\"\n";
            $envContent .= "MAINTENANCE_RETRY_AFTER=3600\n";
            
            file_put_contents($envFile, $envContent);
            return;
        }
        
        $envContent = file_get_contents($envFile);
        
        // Check if the key exists
        if (preg_match("/^{$key}=.*$/m", $envContent)) {
            // Update existing key
            $envContent = preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $envContent);
        } else {
            // Add new key
            $envContent .= "\n{$key}={$value}\n";
        }
        
        file_put_contents($envFile, $envContent);
    }
}
