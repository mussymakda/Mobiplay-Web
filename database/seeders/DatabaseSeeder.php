<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            PackageSeeder::class,
            OfferSeeder::class,
            AdSeeder::class,             // Enable for demo campaigns
            PaymentSeeder::class,        // Enable for demo transactions
            TransactionSeeder::class,    // Enable for demo transactions
            ImpressionSeeder::class,     // Enable for demo impressions
        ]);
    }
}
