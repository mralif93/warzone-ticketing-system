<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seats = [];
        $seatId = 1;
        
        // Define arena sections and pricing zones
        $sections = [
            // VIP Section (Front rows)
            ['sections' => ['A', 'B', 'C'], 'rows' => range(1, 3), 'price_zone' => 'VIP', 'base_price' => 150.00, 'seat_type' => 'VIP'],
            
            // Premium Section (Lower bowl)
            ['sections' => ['D', 'E', 'F', 'G', 'H'], 'rows' => range(1, 15), 'price_zone' => 'Premium', 'base_price' => 100.00, 'seat_type' => 'Premium'],
            
            // Standard Section (Upper bowl)
            ['sections' => ['I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'], 'rows' => range(1, 20), 'price_zone' => 'Standard', 'base_price' => 75.00, 'seat_type' => 'Standard'],
            
            // Economy Section (Back rows)
            ['sections' => ['Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'], 'rows' => range(1, 25), 'price_zone' => 'Economy', 'base_price' => 50.00, 'seat_type' => 'Economy'],
        ];
        
        foreach ($sections as $sectionGroup) {
            foreach ($sectionGroup['sections'] as $section) {
                foreach ($sectionGroup['rows'] as $row) {
                    // Calculate seats per row based on section
                    $seatsPerRow = $this->getSeatsPerRow($section, $row);
                    
                    for ($seatNumber = 1; $seatNumber <= $seatsPerRow; $seatNumber++) {
                        $seats[] = [
                            'id' => $seatId++,
                            'section' => $section,
                            'row' => (string)$row,
                            'number' => $seatNumber,
                            'price_zone' => $sectionGroup['price_zone'],
                            'base_price' => $sectionGroup['base_price'],
                            'seat_type' => $sectionGroup['seat_type'],
                            'is_accessible' => $this->isAccessibleSeat($section, $row, $seatNumber),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        
                        // Insert in batches of 1000 for performance
                        if (count($seats) >= 1000) {
                            DB::table('seats')->insert($seats);
                            $seats = [];
                        }
                    }
                }
            }
        }
        
        // Insert remaining seats
        if (!empty($seats)) {
            DB::table('seats')->insert($seats);
        }
        
        $this->command->info('Created ' . ($seatId - 1) . ' seats for the 7,000-seat arena');
    }
    
    /**
     * Calculate seats per row based on section and row
     */
    private function getSeatsPerRow(string $section, int $row): int
    {
        // VIP sections have fewer seats per row
        if (in_array($section, ['A', 'B', 'C'])) {
            return 20;
        }
        
        // Premium sections
        if (in_array($section, ['D', 'E', 'F', 'G', 'H'])) {
            return 25;
        }
        
        // Standard sections
        if (in_array($section, ['I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'])) {
            return 30;
        }
        
        // Economy sections
        if (in_array($section, ['Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'])) {
            return 35;
        }
        
        return 30; // Default
    }
    
    /**
     * Determine if a seat is accessible
     */
    private function isAccessibleSeat(string $section, int $row, int $seatNumber): bool
    {
        // Accessible seats are typically in the first few rows of each section
        // and at specific seat numbers (usually at the ends of rows)
        return $row <= 3 && ($seatNumber == 1 || $seatNumber == $this->getSeatsPerRow($section, $row));
    }
}