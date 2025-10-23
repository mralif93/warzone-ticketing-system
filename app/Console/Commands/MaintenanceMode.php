<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MaintenanceMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance {action : on|off|status} {--message= : Custom maintenance message} {--ips= : Comma-separated list of allowed IPs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable, disable, or check status of maintenance mode using environment variables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        
        switch ($action) {
            case 'on':
                $this->enableMaintenanceMode();
                break;
            case 'off':
                $this->disableMaintenanceMode();
                break;
            case 'status':
                $this->showStatus();
                break;
            default:
                $this->error('Invalid action. Use "on", "off", or "status".');
                return 1;
        }
        
        return 0;
    }
    
    /**
     * Enable maintenance mode
     */
    private function enableMaintenanceMode()
    {
        // Update .env file
        $this->updateEnvFile('MAINTENANCE_MODE', 'true');
        
        // Update maintenance message if provided
        if ($message = $this->option('message')) {
            $this->updateEnvFile('MAINTENANCE_MESSAGE', '"' . $message . '"');
        }
        
        // Update allowed IPs if provided
        if ($ips = $this->option('ips')) {
            $this->updateEnvFile('MAINTENANCE_ALLOWED_IPS', '"' . $ips . '"');
        }
        
        $this->info('Maintenance mode enabled successfully!');
        $this->line('All requests will be redirected to the maintenance page.');
        $this->line('To disable: php artisan maintenance off');
        $this->line('To check status: php artisan maintenance status');
    }
    
    /**
     * Disable maintenance mode
     */
    private function disableMaintenanceMode()
    {
        // Update .env file
        $this->updateEnvFile('MAINTENANCE_MODE', 'false');
        
        $this->info('Maintenance mode disabled successfully!');
        $this->line('Normal site operation resumed.');
    }
    
    /**
     * Show maintenance mode status
     */
    private function showStatus()
    {
        $isEnabled = config('app.maintenance_mode', false);
        $message = config('app.maintenance_message', 'Default message');
        $allowedIps = config('app.maintenance_allowed_ips', '127.0.0.1,::1');
        $retryAfter = config('app.maintenance_retry_after', 3600);
        
        $this->info('Maintenance Mode Status:');
        $this->line('Enabled: ' . ($isEnabled ? 'Yes' : 'No'));
        $this->line('Message: ' . $message);
        $this->line('Allowed IPs: ' . $allowedIps);
        $this->line('Retry After: ' . $retryAfter . ' seconds');
        
        if ($isEnabled) {
            $this->warn('The application is currently in maintenance mode.');
        } else {
            $this->info('The application is running normally.');
        }
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
