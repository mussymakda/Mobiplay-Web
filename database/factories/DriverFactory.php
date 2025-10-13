<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $driverName = fake()->firstName().' '.fake()->lastName();

        return [
            'name' => $driverName,
            'device_id' => 'tablet_'.fake()->unique()->uuid(),
            'vehicle_number' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'status' => fake()->randomElement(['available', 'busy', 'offline']),
            'current_latitude' => fake()->latitude(19.0, 20.0), // Mexico City area
            'current_longitude' => fake()->longitude(-99.5, -98.5), // Mexico City area
            'daily_distance_km' => fake()->randomFloat(2, 0, 300),
            'is_active' => fake()->boolean(80), // 80% chance of being active
            'last_location_update' => fake()->dateTimeBetween('-2 hours', 'now'),
        ];
    }

    /**
     * Create drivers with Mexican locations and names
     */
    public function mexican(): static
    {
        $mexicanFirstNames = [
            'José', 'Luis', 'Juan', 'Miguel', 'Carlos', 'Antonio', 'Francisco', 'Alejandro', 'Rafael', 'Roberto',
            'María', 'Ana', 'Carmen', 'Rosa', 'Elena', 'Patricia', 'Laura', 'Silvia', 'Claudia', 'Mónica',
        ];

        $mexicanLastNames = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González', 'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Cruz',
            'Torres', 'Flores', 'Gómez', 'Díaz', 'Morales', 'Jiménez', 'Álvarez', 'Ruiz', 'Castillo', 'Ortega',
        ];

        // Major Mexican cities with realistic coordinates
        $mexicanCities = [
            ['name' => 'Ciudad de México', 'lat' => 19.4326, 'lng' => -99.1332],
            ['name' => 'Guadalajara', 'lat' => 20.6597, 'lng' => -103.3496],
            ['name' => 'Monterrey', 'lat' => 25.6866, 'lng' => -100.3161],
            ['name' => 'Puebla', 'lat' => 19.0414, 'lng' => -98.2063],
            ['name' => 'Tijuana', 'lat' => 32.5149, 'lng' => -117.0382],
            ['name' => 'León', 'lat' => 21.1619, 'lng' => -101.6971],
            ['name' => 'Juárez', 'lat' => 31.6904, 'lng' => -106.4245],
            ['name' => 'Querétaro', 'lat' => 20.5888, 'lng' => -100.3899],
            ['name' => 'Mérida', 'lat' => 20.9674, 'lng' => -89.5926],
        ];

        $firstName = fake()->randomElement($mexicanFirstNames);
        $lastName = fake()->randomElement($mexicanLastNames);
        $city = fake()->randomElement($mexicanCities);

        // Add some random offset to coordinates (within ~5km radius)
        $latOffset = (fake()->numberBetween(-50, 50)) / 1000; // ±0.05 degrees ≈ ±5km
        $lngOffset = (fake()->numberBetween(-50, 50)) / 1000;

        return $this->state(fn (array $attributes) => [
            'name' => $firstName.' '.$lastName,
            'device_id' => 'tablet_mx_'.fake()->unique()->uuid(),
            'vehicle_number' => fake()->regexify('[A-Z]{3}-[0-9]{2}-[0-9]{2}'),
            'current_latitude' => $city['lat'] + $latOffset,
            'current_longitude' => $city['lng'] + $lngOffset,
            'last_location_update' => fake()->dateTimeBetween('-2 hours', 'now'),
            'status' => fake()->randomElement(['available', 'busy', 'offline']),
            'is_active' => true,
        ]);
    }

    /**
     * Create an online/available driver (actively driving with tablet)
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'available',
            'is_active' => true,
            'last_location_update' => fake()->dateTimeBetween('-30 minutes', 'now'),
        ]);
    }

    /**
     * Create a busy driver (transporting passengers)
     */
    public function busy(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'busy',
            'is_active' => true,
            'last_location_update' => fake()->dateTimeBetween('-5 minutes', 'now'),
        ]);
    }

    /**
     * Create an offline driver (tablet not active)
     */
    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'offline',
            'is_active' => false,
            'last_location_update' => fake()->dateTimeBetween('-4 hours', '-1 hour'),
        ]);
    }
}
