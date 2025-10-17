<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Offer;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $ads = Ad::all();
        $offers = Offer::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');

            return;
        }

        $this->command->info('Creating transactions for users...');

        foreach ($users as $user) {
            // Create initial deposit transactions
            $depositCount = rand(2, 5);
            for ($i = 0; $i < $depositCount; $i++) {
                $amount = rand(50, 500);
                $createdAt = Carbon::now()->subDays(rand(1, 60))->subHours(rand(0, 23));

                Transaction::create([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => Transaction::TYPE_DEPOSIT,
                    'amount' => $amount,
                    'status' => Transaction::STATUS_COMPLETED,
                    'reference' => 'stripe_'.strtoupper(substr(md5(uniqid()), 0, 12)),
                    'metadata' => [
                        'stripe_payment_intent' => 'pi_'.strtoupper(substr(md5(uniqid()), 0, 20)),
                        'payment_method' => 'card_ending_'.rand(1000, 9999),
                    ],
                    'description' => "Deposit of $amount via Stripe",
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Create bonus transactions if offers exist
            if (! $offers->isEmpty() && rand(1, 3) === 1) { // 33% chance
                $offer = $offers->random();
                $bonusAmount = rand(10, 50);
                $createdAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23));

                Transaction::create([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => Transaction::TYPE_BONUS,
                    'amount' => $bonusAmount,
                    'status' => Transaction::STATUS_COMPLETED,
                    'reference' => 'bonus_'.strtoupper(substr(md5(uniqid()), 0, 8)),
                    'metadata' => [
                        'offer_id' => $offer->id,
                        'offer_name' => $offer->name,
                    ],
                    'description' => "Bonus from offer: {$offer->name}",
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Create ad spending transactions for user's ads
            $userAds = $ads->where('user_id', $user->id);
            foreach ($userAds as $ad) {
                // Create multiple spending transactions per ad
                $spendingTransactions = rand(5, 20);
                $totalSpent = 0;

                for ($i = 0; $i < $spendingTransactions; $i++) {
                    $spentAmount = rand(5, 50) / 10; // $0.50 to $5.00
                    $totalSpent += $spentAmount;
                    $createdAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23));

                    Transaction::create([
                        'user_id' => $user->id,
                        'ad_id' => $ad->id,
                        'type' => Transaction::TYPE_AD_SPEND,
                        'amount' => -$spentAmount, // Negative for spending
                        'status' => Transaction::STATUS_COMPLETED,
                        'reference' => 'ad_'.$ad->id.'_'.strtoupper(substr(md5(uniqid()), 0, 8)),
                        'metadata' => [
                            'campaign_name' => $ad->campaign_name,
                            'impressions' => rand(10, 200),
                            'qr_scans' => rand(1, 20),
                        ],
                        'description' => "Ad spend for campaign: {$ad->campaign_name}",
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }

            // Occasionally create refund transactions
            if (rand(1, 5) === 1) { // 20% chance
                $refundAmount = rand(10, 100);
                $createdAt = Carbon::now()->subDays(rand(1, 15))->subHours(rand(0, 23));

                Transaction::create([
                    'user_id' => $user->id,
                    'ad_id' => null,
                    'type' => Transaction::TYPE_REFUND,
                    'amount' => $refundAmount,
                    'status' => Transaction::STATUS_COMPLETED,
                    'reference' => 'refund_'.strtoupper(substr(md5(uniqid()), 0, 10)),
                    'metadata' => [
                        'reason' => 'Customer requested refund',
                        'original_transaction' => 'stripe_'.strtoupper(substr(md5(uniqid()), 0, 12)),
                    ],
                    'description' => 'Refund processed for customer request',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            $this->command->info("Created transactions for user: {$user->name}");
        }

        // Create some pending transactions for testing
        $randomUsers = $users->random(3);
        foreach ($randomUsers as $user) {
            Transaction::create([
                'user_id' => $user->id,
                'ad_id' => null,
                'type' => Transaction::TYPE_DEPOSIT,
                'amount' => rand(25, 200),
                'status' => Transaction::STATUS_PENDING,
                'reference' => 'pending_'.strtoupper(substr(md5(uniqid()), 0, 10)),
                'metadata' => [
                    'payment_intent' => 'pi_pending_'.strtoupper(substr(md5(uniqid()), 0, 15)),
                ],
                'description' => 'Pending deposit transaction',
                'created_at' => Carbon::now()->subMinutes(rand(5, 120)),
                'updated_at' => Carbon::now()->subMinutes(rand(5, 120)),
            ]);
        }

        $this->command->info('Transaction seeding completed!');
    }
}
