<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user with predefined credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::updateOrCreate(
            ['email' => 'mustansir.makda@gmail.com'],
            [
                'name' => 'Mustansir Makda',
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );

        $this->info("User created: {$user->name} ({$user->email})");
        return Command::SUCCESS;
    }
}
