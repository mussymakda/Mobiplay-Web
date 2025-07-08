<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepositService
{
    /**
     * Process a deposit with automatic offer application
     */
    public function processDeposit(User $user, float $amount, array $metadata = []): Payment
    {
        return DB::transaction(function () use ($user, $amount, $metadata) {
            // Create the main deposit payment
            $payment = $user->payments()->create([
                'amount' => $amount,
                'type' => Payment::TYPE_DEPOSIT,
                'status' => Payment::STATUS_PENDING,
                'stripe_payment_id' => $metadata['stripe_payment_id'] ?? null,
                'stripe_customer_id' => $metadata['stripe_customer_id'] ?? null,
                'transaction_id' => $metadata['transaction_id'] ?? $this->generateTransactionId(),
                'description' => 'Deposit',
                'metadata' => $metadata,
            ]);

            // Check for applicable offers
            $offer = $this->findApplicableOffer($user, $amount);
            
            if ($offer) {
                $bonusAmount = $this->calculateBonus($offer, $amount);
                
                if ($bonusAmount > 0) {
                    // Update the payment with offer details
                    $payment->update([
                        'offer_id' => $offer->id,
                        'bonus_amount' => $bonusAmount,
                        'description' => "Deposit with {$offer->name}",
                    ]);

                    // Increment offer usage
                    $offer->increment('used_count');
                }
            }

            return $payment;
        });
    }

    /**
     * Complete a pending deposit (call this after Stripe confirmation)
     */
    public function completeDeposit(Payment $payment): bool
    {
        if ($payment->status !== Payment::STATUS_PENDING) {
            return false;
        }

        return DB::transaction(function () use ($payment) {
            // Update payment status
            $payment->update(['status' => Payment::STATUS_COMPLETED]);

            // Add amount to user's balance
            $payment->user->addBalance($payment->amount);

            // Add bonus if applicable
            if ($payment->bonus_amount > 0) {
                $payment->user->addBonusBalance(
                    $payment->bonus_amount,
                    $payment->offer_id,
                    "Bonus from {$payment->offer->name}"
                );
            }

            return true;
        });
    }

    /**
     * Find the best applicable offer for a user and deposit amount
     */
    protected function findApplicableOffer(User $user, float $amount): ?Offer
    {
        $now = Carbon::now();
        
        // Get all active offers that the user is eligible for
        $offers = Offer::where('is_active', true)
            ->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now)
            ->where(function ($query) use ($amount) {
                $query->whereNull('minimum_deposit')
                      ->orWhere('minimum_deposit', '<=', $amount);
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                      ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->get();

        // Check if user is eligible for first deposit offers
        $hasFirstDeposit = $user->payments()
            ->where('type', Payment::TYPE_DEPOSIT)
            ->where('status', Payment::STATUS_COMPLETED)
            ->exists();

        $eligibleOffers = $offers->filter(function ($offer) use ($hasFirstDeposit) {
            // First deposit offers only for users without completed deposits
            if ($offer->type === Offer::TYPE_FIRST_DEPOSIT) {
                return !$hasFirstDeposit;
            }
            
            // Other offers are generally available
            return true;
        });

        // Return the offer with the highest bonus value
        return $eligibleOffers->sortByDesc(function ($offer) use ($amount) {
            return $this->calculateBonus($offer, $amount);
        })->first();
    }

    /**
     * Calculate bonus amount for an offer and deposit
     */
    protected function calculateBonus(Offer $offer, float $amount): float
    {
        $bonus = 0;

        if ($offer->bonus_percentage > 0) {
            $bonus = $amount * ($offer->bonus_percentage / 100);
        } elseif ($offer->bonus_fixed_amount > 0) {
            $bonus = $offer->bonus_fixed_amount;
        }

        // Apply maximum bonus limit if set
        if ($offer->maximum_bonus > 0) {
            $bonus = min($bonus, $offer->maximum_bonus);
        }

        return round($bonus, 2);
    }

    /**
     * Generate a unique transaction ID
     */
    protected function generateTransactionId(): string
    {
        return 'txn_' . uniqid() . '_' . random_int(1000, 9999);
    }

    /**
     * Handle auto-debit for ad spending
     */
    public function processAutoDebit(User $user, float $amount, array $metadata = []): ?Payment
    {
        if (!$user->auto_debit_enabled || $user->total_balance >= $amount) {
            return null; // No auto-debit needed
        }

        $needed = $amount - $user->total_balance;
        $debitAmount = max($needed, $user->auto_debit_threshold ?? 50);

        // Create auto-debit payment
        $payment = $user->payments()->create([
            'amount' => $debitAmount,
            'type' => Payment::TYPE_AUTO_DEBIT,
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => $this->generateTransactionId(),
            'description' => 'Auto-debit for ad spending',
            'metadata' => array_merge($metadata, [
                'auto_debit' => true,
                'triggered_by_spend' => $amount,
            ]),
        ]);

        // Here you would integrate with Stripe to charge the user's saved payment method
        // For now, we'll simulate immediate completion
        $this->completeDeposit($payment);

        return $payment;
    }

    /**
     * Process ad spending
     */
    public function processAdSpend(User $user, float $amount, array $metadata = []): Payment
    {
        return DB::transaction(function () use ($user, $amount, $metadata) {
            // Try auto-debit if needed
            if ($user->total_balance < $amount && $user->auto_debit_enabled) {
                $this->processAutoDebit($user, $amount, $metadata);
            }

            // Check if user has sufficient balance after auto-debit
            if ($user->fresh()->total_balance < $amount) {
                throw new \Exception('Insufficient balance for ad spend');
            }

            // Deduct from balance
            $user->deductBalance($amount, Payment::TYPE_AD_SPEND, 'Ad campaign spending');

            // Create spending record
            return $user->payments()->create([
                'amount' => -$amount,
                'type' => Payment::TYPE_AD_SPEND,
                'status' => Payment::STATUS_COMPLETED,
                'transaction_id' => $this->generateTransactionId(),
                'description' => 'Ad campaign spending',
                'metadata' => $metadata,
            ]);
        });
    }
}
