<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Creating users...\n";

        // Main user
        User::updateOrCreate(
            ['email' => 'mustansir.makda@gmail.com'],
            [
                'name' => 'Mustansir Makda',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'balance' => 1000.00,
                'bonus_balance' => 50.00,
                'auto_debit_enabled' => true,
                'auto_debit_threshold' => 100.00,
                'phone_number' => '+1 555-0101',
                'address_line1' => '123 Main Street',
                'city' => 'New York',
                'state_province' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
            ]
        );

        // Demo business users
        $demoUsers = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@coffeeshop.com',
                'balance' => 500.00,
                'phone_number' => '+1 555-0102',
                'address_line1' => '456 Coffee Avenue',
                'city' => 'Los Angeles',
                'state_province' => 'CA',
                'postal_code' => '90210',
                'country' => 'USA',
            ],
            [
                'name' => 'Mike Rodriguez',
                'email' => 'mike@fitnessplus.com',
                'balance' => 750.00,
                'phone_number' => '+1 555-0103',
                'address_line1' => '789 Gym Street',
                'city' => 'Miami',
                'state_province' => 'FL',
                'postal_code' => '33101',
                'country' => 'USA',
            ],
            [
                'name' => 'Emily Chen',
                'email' => 'emily@techstartup.com',
                'balance' => 1200.00,
                'phone_number' => '+1 555-0104',
                'address_line1' => '321 Innovation Drive',
                'city' => 'San Francisco',
                'state_province' => 'CA',
                'postal_code' => '94105',
                'country' => 'USA',
            ],
        ];

        foreach ($demoUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'auto_debit_enabled' => true,
                    'auto_debit_threshold' => 50.00,
                ])
            );
        }

        echo "Users created successfully!\n";
    }
}
