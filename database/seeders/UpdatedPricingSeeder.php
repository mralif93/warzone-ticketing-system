<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Event;

class UpdatedPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing seats
        Seat::truncate();
        
        // Note: Seats are not tied to specific events, they're general configurations

        $seatData = [
            // Premium Categories
            'Warzone Exclusive' => ['price' => 350, 'count' => 100, 'sections' => ['EXC']],
            'Warzone VIP' => ['price' => 250, 'count' => 28, 'sections' => ['VIP']],
            'Warzone Grandstand' => ['price' => 220, 'count' => 60, 'sections' => ['GST']],
            'Warzone Premium Ringside' => ['price' => 199, 'count' => 1716, 'sections' => ['PRS']],
            
            // Level-based Categories
            'Level 1 Zone A' => ['price' => 129, 'count' => 486, 'sections' => ['L1A']],
            'Level 1 Zone B' => ['price' => 129, 'count' => 486, 'sections' => ['L1B']],
            'Level 1 Zone C' => ['price' => 129, 'count' => 486, 'sections' => ['L1C']],
            'Level 1 Zone D' => ['price' => 129, 'count' => 486, 'sections' => ['L1D']],
            'Level 2 Zone A' => ['price' => 89, 'count' => 841, 'sections' => ['L2A']],
            'Level 2 Zone B' => ['price' => 89, 'count' => 841, 'sections' => ['L2B']],
            'Level 2 Zone C' => ['price' => 89, 'count' => 841, 'sections' => ['L2C']],
            'Level 2 Zone D' => ['price' => 89, 'count' => 841, 'sections' => ['L2D']],
            
            // Standing Areas
            'Standing Zone A' => ['price' => 49, 'count' => 150, 'sections' => ['STA']],
            'Standing Zone B' => ['price' => 49, 'count' => 150, 'sections' => ['STB']],
        ];

        $totalSeats = 0;
        $seatId = 1;

        foreach ($seatData as $zone => $data) {
            $sections = $data['sections'];
            $seatsPerSection = intval($data['count'] / count($sections));
            $remainingSeats = $data['count'] % count($sections);

            foreach ($sections as $index => $section) {
                $seatsInThisSection = $seatsPerSection + ($index < $remainingSeats ? 1 : 0);
                
                for ($i = 1; $i <= $seatsInThisSection; $i++) {
                    $row = str_pad(ceil($i / 20), 2, '0', STR_PAD_LEFT);
                    $seatNumber = str_pad(($i % 20) ?: 20, 2, '0', STR_PAD_LEFT);
                    
                    Seat::create([
                        'section' => $section,
                        'row' => $row,
                        'number' => $seatNumber,
                        'price_zone' => $zone,
                        'base_price' => $data['price'],
                        'seat_type' => $this->getSeatType($zone),
                        'is_accessible' => $this->isAccessibleSeat($zone),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $totalSeats++;
                    $seatId++;
                }
            }
        }

        $this->command->info("Created {$totalSeats} seats with updated pricing structure.");
        
        // Display summary
        $this->command->info("\nPricing Summary:");
        foreach ($seatData as $zone => $data) {
            $actualCount = Seat::where('price_zone', $zone)->count();
            $this->command->info("{$zone}: RM{$data['price']} - {$actualCount} seats");
        }
    }

    /**
     * Get seat type based on price zone
     */
    private function getSeatType(string $zone): string
    {
        if (str_contains($zone, 'Exclusive') || str_contains($zone, 'VIP')) {
            return 'VIP';
        } elseif (str_contains($zone, 'Premium') || str_contains($zone, 'Grandstand')) {
            return 'Premium';
        } else {
            return 'Standard';
        }
    }

    /**
     * Check if seat should be accessible
     */
    private function isAccessibleSeat(string $zone): bool
    {
        // Make some seats in each category accessible (about 5%)
        return rand(1, 100) <= 5;
    }
}
