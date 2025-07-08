<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use App\Models\Offer;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $offers = Offer::where('is_active', true)->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $payments = [];
        
        // Create various types of payments
        foreach ($users->take(10) as $index => $user) {
            // First deposit with welcome bonus
            $welcomeOffer = $offers->where('type', Offer::TYPE_FIRST_DEPOSIT)->first();
            $depositAmount = fake()->randomFloat(2, 50, 500);
            $bonusAmount = $welcomeOffer ? min($depositAmount * ($welcomeOffer->bonus_percentage / 100), $welcomeOffer->maximum_bonus ?? $depositAmount) : 0;
            
            $payments[] = [
                'user_id' => $user->id,
                'amount' => $depositAmount,
                'type' => Payment::TYPE_DEPOSIT,
                'status' => Payment::STATUS_COMPLETED,
                'stripe_payment_id' => 'pi_' . fake()->regexify('[A-Za-z0-9]{24}'),
                'stripe_customer_id' => 'cus_' . fake()->regexify('[A-Za-z0-9]{14}'),
                'transaction_id' => 'txn_' . fake()->regexify('[A-Za-z0-9]{16}'),
                'offer_id' => $welcomeOffer?->id,
                'bonus_amount' => $bonusAmount,
                'description' => 'Welcome deposit with ' . ($welcomeOffer?->name ?? 'no bonus'),
                'metadata' => [
                    'payment_method' => fake()->randomElement(['card', 'bank_transfer']),
                    'ip_address' => fake()->ipv4(),
                    'user_agent' => fake()->userAgent(),
                ],
                'created_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
                'updated_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
            ];

            // Bonus payment (if applicable)
            if ($bonusAmount > 0) {
                $payments[] = [
                    'user_id' => $user->id,
                    'amount' => $bonusAmount,
                    'type' => Payment::TYPE_BONUS,
                    'status' => Payment::STATUS_COMPLETED,
                    'stripe_payment_id' => null,
                    'stripe_customer_id' => null,
                    'transaction_id' => 'bonus_' . fake()->regexify('[A-Za-z0-9]{12}'),
                    'offer_id' => $welcomeOffer?->id,
                    'bonus_amount' => 0.00,
                    'description' => 'Welcome bonus for deposit',
                    'metadata' => [
                        'related_deposit_id' => count($payments), // Reference to the deposit
                        'bonus_type' => 'welcome_bonus',
                    ],
                    'created_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
                    'updated_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
                ];
            }

            // Some reload deposits
            if ($index % 3 === 0) {
                $reloadOffer = $offers->where('type', Offer::TYPE_RELOAD_BONUS)->first();
                $reloadAmount = fake()->randomFloat(2, 100, 300);
                $reloadBonus = $reloadOffer ? min($reloadAmount * ($reloadOffer->bonus_percentage / 100), $reloadOffer->maximum_bonus ?? $reloadAmount) : 0;
                
                $payments[] = [
                    'user_id' => $user->id,
                    'amount' => $reloadAmount,
                    'type' => Payment::TYPE_DEPOSIT,
                    'status' => Payment::STATUS_COMPLETED,
                    'stripe_payment_id' => 'pi_' . fake()->regexify('[A-Za-z0-9]{24}'),
                    'stripe_customer_id' => 'cus_' . fake()->regexify('[A-Za-z0-9]{14}'),
                    'transaction_id' => 'txn_' . fake()->regexify('[A-Za-z0-9]{16}'),
                    'offer_id' => $reloadOffer?->id,
                    'bonus_amount' => $reloadBonus,
                    'description' => 'Reload deposit with bonus',
                    'metadata' => [
                        'payment_method' => fake()->randomElement(['card', 'bank_transfer']),
                        'ip_address' => fake()->ipv4(),
                    ],
                    'created_at' => Carbon::now()->subDays(fake()->numberBetween(1, 15)),
                    'updated_at' => Carbon::now()->subDays(fake()->numberBetween(1, 15)),
                ];
            }

            // Some ad spending
            $adSpendAmount = fake()->randomFloat(2, 10, 200);
            $payments[] = [
                'user_id' => $user->id,
                'amount' => $adSpendAmount,
                'type' => Payment::TYPE_AD_SPEND,
                'status' => Payment::STATUS_COMPLETED,
                'stripe_payment_id' => null,
                'stripe_customer_id' => null,
                'transaction_id' => 'spend_' . fake()->regexify('[A-Za-z0-9]{12}'),
                'offer_id' => null,
                'bonus_amount' => 0.00,
                'description' => 'Ad campaign spending',
                'metadata' => [
                    'campaign_id' => fake()->uuid(),
                    'campaign_name' => fake()->words(3, true),
                ],
                'created_at' => Carbon::now()->subDays(fake()->numberBetween(1, 7)),
                'updated_at' => Carbon::now()->subDays(fake()->numberBetween(1, 7)),
            ];
        }

        // Add some failed/pending payments
        $user = $users->first();
        $payments[] = [
            'user_id' => $user->id,
            'amount' => 75.00,
            'type' => Payment::TYPE_DEPOSIT,
            'status' => Payment::STATUS_FAILED,
            'stripe_payment_id' => 'pi_' . fake()->regexify('[A-Za-z0-9]{24}'),
            'stripe_customer_id' => 'cus_' . fake()->regexify('[A-Za-z0-9]{14}'),
            'transaction_id' => null,
            'offer_id' => null,
            'bonus_amount' => 0.00,
            'description' => 'Failed payment - insufficient funds',
            'metadata' => [
                'failure_code' => 'insufficient_funds',
                'failure_message' => 'Your card was declined.',
            ],
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ];

        $payments[] = [
            'user_id' => $user->id,
            'amount' => 150.00,
            'type' => Payment::TYPE_DEPOSIT,
            'status' => Payment::STATUS_PENDING,
            'stripe_payment_id' => 'pi_' . fake()->regexify('[A-Za-z0-9]{24}'),
            'stripe_customer_id' => 'cus_' . fake()->regexify('[A-Za-z0-9]{14}'),
            'transaction_id' => null,
            'offer_id' => null,
            'bonus_amount' => 0.00,
            'description' => 'Pending bank transfer',
            'metadata' => [
                'payment_method' => 'bank_transfer',
                'expected_completion' => Carbon::now()->addDays(1)->toISOString(),
            ],
            'created_at' => Carbon::now()->subMinutes(30),
            'updated_at' => Carbon::now()->subMinutes(30),
        ];

        foreach ($payments as $paymentData) {
            Payment::create($paymentData);
        }

        $this->command->info('Created ' . count($payments) . ' sample payments');
    }
}
