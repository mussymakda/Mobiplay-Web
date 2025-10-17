<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'priority_level',
        'priority_text',
        'description',
        'ad_showing_conditions',
        'cost_per_impression',
        'cost_per_qr_scan',
        'is_active',
    ];

    protected $casts = [
        'cost_per_impression' => 'decimal:4',
        'cost_per_qr_scan' => 'decimal:4',
        'is_active' => 'boolean',
        'priority_level' => 'integer',
        'ad_showing_conditions' => 'array',
    ];

    // Scope for active packages
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get formatted cost per impression
    public function getFormattedCostAttribute()
    {
        return '$'.number_format($this->cost_per_impression, 4);
    }

    // Get formatted cost per QR scan
    public function getFormattedQrCostAttribute()
    {
        return '$'.number_format($this->cost_per_qr_scan, 4);
    }

    // Get formatted ad showing conditions
    public function getFormattedConditionsAttribute()
    {
        if (! $this->ad_showing_conditions) {
            return 'No conditions set';
        }

        $conditions = collect($this->ad_showing_conditions)->map(function ($condition) {
            return match ($condition) {
                'rush_hours' => 'Rush Hours',
                'normal_hours' => 'Normal Hours',
                'holidays' => 'Holidays',
                default => ucfirst(str_replace('_', ' ', $condition))
            };
        });

        return $conditions->join(', ');
    }

    // Available ad showing conditions
    public static function getAvailableConditions()
    {
        return [
            'rush_hours' => 'Rush Hours',
            'normal_hours' => 'Normal Hours',
            'holidays' => 'Holidays',
        ];
    }
}
