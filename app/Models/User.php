<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
        'email_verified_at',
        'stripe_customer_id',
        'metered_subscription_id',
        'balance',
        'bonus_balance',
        'auto_debit_enabled',
        'auto_debit_threshold',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'bonus_balance' => 'decimal:2',
            'auto_debit_enabled' => 'boolean',
            'auto_debit_threshold' => 'decimal:2',
        ];
    }

    // Relationships
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function impressions()
    {
        return $this->hasMany(Impression::class);
    }

    // Balance management methods
    public function getTotalBalanceAttribute()
    {
        return $this->balance + $this->bonus_balance;
    }

    public function addBalance($amount, $type = 'deposit', $description = null)
    {
        // Validate that the type is a valid enum value
        $validTypes = [
            Payment::TYPE_DEPOSIT,
            Payment::TYPE_AUTO_DEBIT,
            Payment::TYPE_BONUS,
            Payment::TYPE_AD_SPEND,
            Payment::TYPE_REFUND,
        ];

        if (! in_array($type, $validTypes)) {
            // If invalid type, use it as description and default to refund type
            $description = $description ?? $type;
            $type = Payment::TYPE_REFUND;
        }

        $this->increment('balance', $amount);

        // Create payment record
        return $this->payments()->create([
            'amount' => $amount,
            'type' => $type,
            'status' => Payment::STATUS_COMPLETED,
            'description' => $description ?? "Balance added: $amount",
            'transaction_id' => 'manual_'.uniqid(),
        ]);
    }

    public function addBonusBalance($amount, $offerId = null, $description = null)
    {
        $this->increment('bonus_balance', $amount);

        // Create payment record
        return $this->payments()->create([
            'amount' => 0,
            'bonus_amount' => $amount,
            'type' => Payment::TYPE_BONUS,
            'status' => Payment::STATUS_COMPLETED,
            'offer_id' => $offerId,
            'description' => $description ?? "Bonus added: $amount",
        ]);
    }

    public function deductBalance($amount, $type = 'ad_spend', $description = null)
    {
        if ($this->total_balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        // Deduct from bonus balance first, then regular balance
        if ($this->bonus_balance >= $amount) {
            $this->decrement('bonus_balance', $amount);
        } else {
            $remaining = $amount - $this->bonus_balance;
            $this->update(['bonus_balance' => 0]);
            $this->decrement('balance', $remaining);
        }

        // Create payment record
        return $this->payments()->create([
            'amount' => -$amount,
            'type' => $type,
            'status' => Payment::STATUS_COMPLETED,
            'description' => $description ?? "Balance deducted: $amount",
        ]);
    }

    public function getFormattedBalanceAttribute()
    {
        return '$'.number_format($this->balance, 2);
    }

    public function getFormattedBonusBalanceAttribute()
    {
        return '$'.number_format($this->bonus_balance, 2);
    }

    public function getFormattedTotalBalanceAttribute()
    {
        return '$'.number_format($this->total_balance, 2);
    }

    public function getTotalImpressionsAttribute()
    {
        return $this->ads()->sum('impressions');
    }

    public function getTotalSpentAttribute()
    {
        return $this->ads()->sum('spent');
    }

    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/'.$this->profile_image);
        }

        return asset('assets/images/demo-profile.svg');
    }
}
