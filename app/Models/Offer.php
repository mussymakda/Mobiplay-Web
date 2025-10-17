<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'bonus_percentage',
        'bonus_fixed_amount',
        'minimum_deposit',
        'maximum_bonus',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'is_active',
        'conditions',
    ];

    protected $casts = [
        'bonus_percentage' => 'decimal:2',
        'bonus_fixed_amount' => 'decimal:2',
        'minimum_deposit' => 'decimal:2',
        'maximum_bonus' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    // Offer types
    const TYPE_FIRST_DEPOSIT = 'first_deposit';

    const TYPE_RELOAD_BONUS = 'reload_bonus';

    const TYPE_PERCENTAGE_BONUS = 'percentage_bonus';

    const TYPE_FIXED_BONUS = 'fixed_bonus';

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = now();

        return $query->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now);
    }

    public function scopeAvailable($query)
    {
        return $query->active()->valid()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }

    // Calculate bonus amount for a given deposit
    public function calculateBonus($depositAmount)
    {
        if (! $this->isEligible($depositAmount)) {
            return 0;
        }

        $bonus = 0;

        if ($this->bonus_percentage > 0) {
            $bonus = ($depositAmount * $this->bonus_percentage) / 100;
        }

        if ($this->bonus_fixed_amount > 0) {
            $bonus += $this->bonus_fixed_amount;
        }

        // Apply maximum bonus limit
        if ($this->maximum_bonus > 0) {
            $bonus = min($bonus, $this->maximum_bonus);
        }

        return round($bonus, 2);
    }

    // Check if offer is eligible for a deposit amount
    public function isEligible($depositAmount)
    {
        if (! $this->is_active) {
            return false;
        }
        if (now() < $this->valid_from || now() > $this->valid_until) {
            return false;
        }
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }
        if ($this->minimum_deposit && $depositAmount < $this->minimum_deposit) {
            return false;
        }

        return true;
    }

    // Check if user can use this offer
    public function canUserUse(User $user)
    {
        if ($this->type === self::TYPE_FIRST_DEPOSIT) {
            // Check if user has made any successful deposits before
            return ! Payment::where('user_id', $user->id)
                ->where('type', Payment::TYPE_DEPOSIT)
                ->where('status', Payment::STATUS_COMPLETED)
                ->exists();
        }

        return true;
    }
}
