<?php

namespace Database\Seeders;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'name' => 'Welcome Bonus - 100% Match',
                'description' => 'Get 100% bonus on your first deposit! Double your advertising budget right from the start.',
                'type' => Offer::TYPE_FIRST_DEPOSIT,
                'bonus_percentage' => 100.00,
                'bonus_fixed_amount' => null,
                'minimum_deposit' => 3000.00,
                'maximum_bonus' => 3000.00,
                'valid_from' => Carbon::now()->subDays(7),
                'valid_until' => Carbon::now()->addMonths(6),
                'usage_limit' => null, // Unlimited for first deposit
                'used_count' => 43,
                'is_active' => true,
                'conditions' => [
                    'Only available for new users',
                    'Minimum deposit of $3000 required',
                    'Maximum bonus amount is $3000',
                    'Bonus funds must be used within 30 days',
                    'Cannot be combined with other offers',
                ],
            ],
            [
                'name' => 'Reload Bonus - 50% Extra',
                'description' => 'Get 50% extra on reload deposits of $100 or more.',
                'type' => Offer::TYPE_RELOAD_BONUS,
                'bonus_percentage' => 50.00,
                'bonus_fixed_amount' => null,
                'minimum_deposit' => 100.00,
                'maximum_bonus' => 250.00,
                'valid_from' => Carbon::now()->subDays(3),
                'valid_until' => Carbon::now()->addMonths(3),
                'usage_limit' => 100,
                'used_count' => 12,
                'is_active' => true,
                'conditions' => [
                    'Available for existing users only',
                    'Minimum reload deposit of $100',
                    'Maximum bonus of $250 per reload',
                    'Limited to 1 use per user per month',
                    'Bonus expires in 14 days if unused',
                ],
            ],
            [
                'name' => 'Weekend Warrior',
                'description' => 'Fixed $75 bonus on weekend deposits of $200 or more.',
                'type' => Offer::TYPE_FIXED_BONUS,
                'bonus_percentage' => null,
                'bonus_fixed_amount' => 75.00,
                'minimum_deposit' => 200.00,
                'maximum_bonus' => 75.00,
                'valid_from' => Carbon::now()->subDays(1),
                'valid_until' => Carbon::now()->addWeeks(8),
                'usage_limit' => 50,
                'used_count' => 8,
                'is_active' => true,
                'conditions' => [
                    'Only available on weekends (Saturday-Sunday)',
                    'Minimum deposit of $200 required',
                    'Fixed bonus of $75',
                    'Limited to 50 total uses',
                    'Cannot be combined with reload bonuses',
                ],
            ],
            [
                'name' => 'High Roller Special',
                'description' => 'Exclusive 25% bonus for deposits over $1000.',
                'type' => Offer::TYPE_PERCENTAGE_BONUS,
                'bonus_percentage' => 25.00,
                'bonus_fixed_amount' => null,
                'minimum_deposit' => 1000.00,
                'maximum_bonus' => 1000.00,
                'valid_from' => Carbon::now()->subDays(14),
                'valid_until' => Carbon::now()->addMonths(12),
                'usage_limit' => 25,
                'used_count' => 3,
                'is_active' => true,
                'conditions' => [
                    'Exclusive offer for high-volume advertisers',
                    'Minimum deposit of $1000 required',
                    'Maximum bonus of $1000',
                    'Limited to 25 total uses',
                    'VIP account status may be required',
                ],
            ],
            [
                'name' => 'Summer Promo - Expired',
                'description' => 'Summer promotional offer that has expired.',
                'type' => Offer::TYPE_FIRST_DEPOSIT,
                'bonus_percentage' => 75.00,
                'bonus_fixed_amount' => null,
                'minimum_deposit' => 50.00,
                'maximum_bonus' => 300.00,
                'valid_from' => Carbon::now()->subMonths(3),
                'valid_until' => Carbon::now()->subMonth(),
                'usage_limit' => 200,
                'used_count' => 156,
                'is_active' => false,
                'conditions' => [
                    'Summer promotional offer',
                    'Valid from June to July only',
                    'Now expired and inactive',
                ],
            ],
            [
                'name' => 'Black Friday Mega Bonus',
                'description' => 'Coming soon! 150% bonus for Black Friday weekend.',
                'type' => Offer::TYPE_FIRST_DEPOSIT,
                'bonus_percentage' => 150.00,
                'bonus_fixed_amount' => null,
                'minimum_deposit' => 25.00,
                'maximum_bonus' => 750.00,
                'valid_from' => Carbon::now()->addMonths(4),
                'valid_until' => Carbon::now()->addMonths(4)->addDays(4),
                'usage_limit' => 500,
                'used_count' => 0,
                'is_active' => false, // Not active yet
                'conditions' => [
                    'Special Black Friday promotion',
                    'Available only during Black Friday weekend',
                    'Highest bonus percentage of the year',
                    'Limited quantity - first come, first served',
                ],
            ],
        ];

        foreach ($offers as $offerData) {
            Offer::create($offerData);
        }

        $this->command->info('Created '.count($offers).' sample offers');
    }
}
