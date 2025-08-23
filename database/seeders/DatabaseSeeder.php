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
            OfferSeeder::class
        ]);
    }
}
            // PaymentSeeder::class,        // Commented out - contains demo data
            // AdSeeder::class,             // Commented out - contains demo data
            // ImpressionSeeder::class,     // Commented out - contains demo data
        ]);
    }
}
