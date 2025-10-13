<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeederTemp extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            PackageSeeder::class,
            OfferSeeder::class
        ]);
    }
}
