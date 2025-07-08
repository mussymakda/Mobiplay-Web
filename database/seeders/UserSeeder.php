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
        echo "Creating user with email: mustansir.makda@gmail.com\n";
        
        User::updateOrCreate(
            ['email' => 'mustansir.makda@gmail.com'],
            [
                'name' => 'Mustansir Makda',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        echo "User created successfully!\n";
    }
}
