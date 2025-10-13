<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

class TabletDeviceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create test tablet devices for Mexico City area
        Driver::factory()
            ->count(15)
            ->mexican()
            ->available()
            ->create();

        // Create some busy tablets
        Driver::factory()
            ->count(8)
            ->mexican()
            ->busy()
            ->create();

        // Create some offline tablets
        Driver::factory()
            ->count(5)
            ->mexican()
            ->offline()
            ->create();

        $this->command->info('Created 28 test tablet devices (15 available, 8 busy, 5 offline)');
    }
}
