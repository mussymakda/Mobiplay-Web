<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;
use App\Models\Impression;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Offer;
use App\Models\User;

class ClearTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clear-test {--confirm : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all test/demo data from the database, keeping only essential data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('confirm') && !$this->confirm('This will delete all test ads, impressions, payments, and transactions. Are you sure?')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Clearing test data...');

        // Clear impressions first (has foreign keys)
        $impressionsCount = Impression::count();
        Impression::truncate();
        $this->info("Cleared {$impressionsCount} impressions");

        // Clear transactions
        $transactionsCount = Transaction::count();
        Transaction::truncate();
        $this->info("Cleared {$transactionsCount} transactions");

        // Clear payments
        $paymentsCount = Payment::count();
        Payment::truncate();
        $this->info("Cleared {$paymentsCount} payments");

        // Clear ads
        $adsCount = Ad::count();
        Ad::truncate();
        $this->info("Cleared {$adsCount} ads");

        // Clear offers
        $offersCount = Offer::count();
        Offer::truncate();
        $this->info("Cleared {$offersCount} offers");

        // Only clear non-admin users (keep the main admin user)
        $usersCount = User::where('email', '!=', 'mustansir.makda@gmail.com')->count();
        User::where('email', '!=', 'mustansir.makda@gmail.com')->delete();
        $this->info("Cleared {$usersCount} test users (kept admin user)");

        $this->info('✅ Test data cleared successfully! The admin panel now shows only real data.');
        $this->info('You can now add your real data through the admin panel.');
    }
}
