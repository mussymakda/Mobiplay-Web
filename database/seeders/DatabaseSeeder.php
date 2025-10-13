<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            PackageSeeder::class,
            OfferSeeder::class,             // Re-enabled for real offers
            // PaymentSeeder::class,        // Commented out - contains demo data
            // AdSeeder::class,             // Commented out - contains demo data
            // ImpressionSeeder::class,     // Commented out - contains demo data
        ]);
    }
}
