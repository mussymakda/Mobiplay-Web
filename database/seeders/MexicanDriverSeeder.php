<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\DriverLocationLog;
use Illuminate\Database\Seeder;

class MexicanDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating Mexican drivers...');

        // Create 50 Mexican drivers with realistic locations
        $drivers = Driver::factory()
            ->count(50)
            ->mexican()
            ->verified()
            ->create();

        // Make 30 of them available and active
        $availableTablets = $drivers->take(30);
        foreach ($availableTablets as $driver) {
            $driver->update([
                'status' => 'available',
                'is_active' => true,
                'last_location_update' => now()->subMinutes(fake()->numberBetween(1, 60)),
            ]);
        }

        // Make 15 of them busy
        $busyDrivers = $drivers->skip(30)->take(15);
        foreach ($busyDrivers as $driver) {
            $driver->update([
                'status' => 'busy',
                'is_active' => true,
                'last_location_update' => now()->subMinutes(fake()->numberBetween(1, 30)),
            ]);
        }

        // Create location history for active drivers
        $this->command->info('Creating location history...');
        foreach ($drivers->take(45) as $driver) { // Skip offline drivers
            // Create 5-15 location logs for each driver over the past 24 hours
            $logCount = fake()->numberBetween(5, 15);

            for ($i = 0; $i < $logCount; $i++) {
                $baseTime = now()->subHours(24)->addMinutes($i * (1440 / $logCount)); // Spread over 24 hours
                $timeVariation = fake()->numberBetween(-30, 30); // ±30 minutes variation

                // Small movements around their base location
                $latOffset = (fake()->numberBetween(-20, 20)) / 1000; // ±0.02 degrees ≈ ±2km
                $lngOffset = (fake()->numberBetween(-20, 20)) / 1000;

                DriverLocationLog::create([
                    'driver_id' => $driver->id,
                    'latitude' => $driver->current_latitude + $latOffset,
                    'longitude' => $driver->current_longitude + $lngOffset,
                    'accuracy' => fake()->numberBetween(3, 50), // meters
                    'speed' => fake()->numberBetween(0, 80), // km/h
                    'heading' => fake()->numberBetween(0, 359), // degrees
                    'source' => fake()->randomElement(['GPS', 'Network', 'location_update']),
                    'recorded_at' => $baseTime->addMinutes($timeVariation),
                    'distance_from_previous' => $i > 0 ? fake()->randomFloat(2, 0.1, 5.0) : 0, // km
                    'time_difference_seconds' => $i > 0 ? fake()->numberBetween(300, 3600) : 0, // 5-60 minutes
                    'estimated_speed_kmh' => fake()->numberBetween(10, 60),
                    'is_suspicious' => fake()->boolean(5), // 5% chance of suspicious activity
                    'metadata' => [
                        'battery_level' => fake()->numberBetween(20, 100),
                        'network_type' => fake()->randomElement(['4G', '5G', 'WiFi']),
                        'app_version' => '1.0.0',
                    ],
                ]);
            }
        }

        $this->command->info("Created {$drivers->count()} Mexican tablet devices with location data.");
        $this->command->info("- {$availableTablets->count()} tablets are available");
        $this->command->info("- {$busyDrivers->count()} tablets are busy");
        $this->command->info('- 5 tablets are offline');
        $this->command->info('- Created realistic location history for all active drivers');
    }
}
