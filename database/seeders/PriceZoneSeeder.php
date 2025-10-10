<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceZone;

class PriceZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priceZones = [
            // Premium Categories
            [
                'name' => 'Warzone Exclusive',
                'code' => 'WZ_EXCLUSIVE',
                'description' => 'The most exclusive seating area with premium amenities and the best views',
                'base_price' => 350.00,
                'category' => 'Premium',
                'color' => '#DC2626',
                'icon' => 'bx-crown',
                'sort_order' => 1,
                'is_active' => true,
                'metadata' => [
                    'amenities' => ['Premium seating', 'VIP access', 'Complimentary refreshments'],
                    'capacity' => 100
                ]
            ],
            [
                'name' => 'Warzone VIP',
                'code' => 'WZ_VIP',
                'description' => 'VIP seating with excellent views and premium services',
                'base_price' => 250.00,
                'category' => 'Premium',
                'color' => '#F59E0B',
                'icon' => 'bx-star',
                'sort_order' => 2,
                'is_active' => true,
                'metadata' => [
                    'amenities' => ['VIP seating', 'Priority access', 'Premium services'],
                    'capacity' => 28
                ]
            ],
            [
                'name' => 'Warzone Grandstand',
                'code' => 'WZ_GRANDSTAND',
                'description' => 'Grandstand seating with elevated views of the action',
                'base_price' => 220.00,
                'category' => 'Premium',
                'color' => '#EF4444',
                'icon' => 'bx-building',
                'sort_order' => 3,
                'is_active' => true,
                'metadata' => [
                    'amenities' => ['Elevated seating', 'Great views', 'Comfortable seating'],
                    'capacity' => 60
                ]
            ],
            [
                'name' => 'Warzone Premium Ringside',
                'code' => 'WZ_PREMIUM_RINGSIDE',
                'description' => 'Premium ringside seating close to the action',
                'base_price' => 199.00,
                'category' => 'Premium',
                'color' => '#F97316',
                'icon' => 'bx-medal',
                'sort_order' => 4,
                'is_active' => true,
                'metadata' => [
                    'amenities' => ['Ringside seating', 'Close to action', 'Premium view'],
                    'capacity' => 1716
                ]
            ],

            // Level-based Categories
            [
                'name' => 'Level 1 Zone A',
                'code' => 'L1_ZONE_A',
                'description' => 'Level 1 seating in Zone A with excellent views',
                'base_price' => 129.00,
                'category' => 'Level',
                'color' => '#3B82F6',
                'icon' => 'bx-layer',
                'sort_order' => 5,
                'is_active' => true,
                'metadata' => [
                    'level' => 1,
                    'zone' => 'A',
                    'capacity' => 486
                ]
            ],
            [
                'name' => 'Level 1 Zone B',
                'code' => 'L1_ZONE_B',
                'description' => 'Level 1 seating in Zone B with excellent views',
                'base_price' => 129.00,
                'category' => 'Level',
                'color' => '#3B82F6',
                'icon' => 'bx-layer',
                'sort_order' => 6,
                'is_active' => true,
                'metadata' => [
                    'level' => 1,
                    'zone' => 'B',
                    'capacity' => 486
                ]
            ],
            [
                'name' => 'Level 1 Zone C',
                'code' => 'L1_ZONE_C',
                'description' => 'Level 1 seating in Zone C with excellent views',
                'base_price' => 129.00,
                'category' => 'Level',
                'color' => '#3B82F6',
                'icon' => 'bx-layer',
                'sort_order' => 7,
                'is_active' => true,
                'metadata' => [
                    'level' => 1,
                    'zone' => 'C',
                    'capacity' => 486
                ]
            ],
            [
                'name' => 'Level 1 Zone D',
                'code' => 'L1_ZONE_D',
                'description' => 'Level 1 seating in Zone D with excellent views',
                'base_price' => 129.00,
                'category' => 'Level',
                'color' => '#3B82F6',
                'icon' => 'bx-layer',
                'sort_order' => 8,
                'is_active' => true,
                'metadata' => [
                    'level' => 1,
                    'zone' => 'D',
                    'capacity' => 486
                ]
            ],
            [
                'name' => 'Level 2 Zone A',
                'code' => 'L2_ZONE_A',
                'description' => 'Level 2 seating in Zone A with good views',
                'base_price' => 89.00,
                'category' => 'Level',
                'color' => '#1D4ED8',
                'icon' => 'bx-layer',
                'sort_order' => 9,
                'is_active' => true,
                'metadata' => [
                    'level' => 2,
                    'zone' => 'A',
                    'capacity' => 841
                ]
            ],
            [
                'name' => 'Level 2 Zone B',
                'code' => 'L2_ZONE_B',
                'description' => 'Level 2 seating in Zone B with good views',
                'base_price' => 89.00,
                'category' => 'Level',
                'color' => '#1D4ED8',
                'icon' => 'bx-layer',
                'sort_order' => 10,
                'is_active' => true,
                'metadata' => [
                    'level' => 2,
                    'zone' => 'B',
                    'capacity' => 841
                ]
            ],
            [
                'name' => 'Level 2 Zone C',
                'code' => 'L2_ZONE_C',
                'description' => 'Level 2 seating in Zone C with good views',
                'base_price' => 89.00,
                'category' => 'Level',
                'color' => '#1D4ED8',
                'icon' => 'bx-layer',
                'sort_order' => 11,
                'is_active' => true,
                'metadata' => [
                    'level' => 2,
                    'zone' => 'C',
                    'capacity' => 841
                ]
            ],
            [
                'name' => 'Level 2 Zone D',
                'code' => 'L2_ZONE_D',
                'description' => 'Level 2 seating in Zone D with good views',
                'base_price' => 89.00,
                'category' => 'Level',
                'color' => '#1D4ED8',
                'icon' => 'bx-layer',
                'sort_order' => 12,
                'is_active' => true,
                'metadata' => [
                    'level' => 2,
                    'zone' => 'D',
                    'capacity' => 841
                ]
            ],

            // Standing Areas
            [
                'name' => 'Standing Zone A',
                'code' => 'STANDING_ZONE_A',
                'description' => 'Standing area in Zone A with general admission',
                'base_price' => 49.00,
                'category' => 'Standing',
                'color' => '#10B981',
                'icon' => 'bx-walk',
                'sort_order' => 13,
                'is_active' => true,
                'metadata' => [
                    'zone' => 'A',
                    'type' => 'Standing',
                    'capacity' => 150
                ]
            ],
            [
                'name' => 'Standing Zone B',
                'code' => 'STANDING_ZONE_B',
                'description' => 'Standing area in Zone B with general admission',
                'base_price' => 49.00,
                'category' => 'Standing',
                'color' => '#059669',
                'icon' => 'bx-walk',
                'sort_order' => 14,
                'is_active' => true,
                'metadata' => [
                    'zone' => 'B',
                    'type' => 'Standing',
                    'capacity' => 150
                ]
            ],
        ];

        foreach ($priceZones as $priceZoneData) {
            PriceZone::create($priceZoneData);
        }

        $this->command->info('Created ' . count($priceZones) . ' price zones successfully.');
        
        // Display summary
        $this->command->info("\nPrice Zone Summary:");
        foreach ($priceZones as $zone) {
            $this->command->info("{$zone['name']}: RM{$zone['base_price']} - {$zone['category']} - {$zone['metadata']['capacity']} seats");
        }
    }
}