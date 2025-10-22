<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'max_tickets_per_order',
                'value' => '10',
                'type' => 'integer',
                'description' => 'Maximum number of tickets a customer can purchase in a single order'
            ],
            [
                'key' => 'seat_hold_duration_minutes',
                'value' => '10',
                'type' => 'integer',
                'description' => 'How long to hold seats during the purchase process (in minutes)'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable maintenance mode to temporarily disable the system'
            ],
            [
                'key' => 'auto_release_holds',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Automatically release held seats when they expire'
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Send email notifications for orders and tickets'
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@warzone.com',
                'type' => 'string',
                'description' => 'Email address for system notifications'
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'integer',
                'description' => 'How long before user sessions expire (in minutes)'
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Maximum failed login attempts before account lockout'
            ],
            [
                'key' => 'service_fee_percentage',
                'value' => '5.0',
                'type' => 'string',
                'description' => 'Service fee percentage applied to ticket orders'
            ],
            [
                'key' => 'tax_percentage',
                'value' => '6.0',
                'type' => 'string',
                'description' => 'Tax percentage applied to ticket orders'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}