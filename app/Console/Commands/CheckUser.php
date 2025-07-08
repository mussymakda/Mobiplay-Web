<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:user {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a user exists with the given email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if ($user) {
            $this->info("User found: {$user->name} ({$user->email})");
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}
