<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Premium Plus',
                'priority_level' => 95,
                'priority_text' => 'Ultra High',
                'description' => 'Maximum visibility package with highest priority placement and all-time coverage.',
                'ad_showing_conditions' => ['rush_hours', 'normal_hours', 'holidays'],
                'cost_per_impression' => 0.0250,
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'priority_level' => 80,
                'priority_text' => 'High',
                'description' => 'High priority package with excellent coverage during peak times.',
                'ad_showing_conditions' => ['rush_hours', 'normal_hours'],
                'cost_per_impression' => 0.0180,
                'is_active' => true,
            ],
            [
                'name' => 'Business',
                'priority_level' => 65,
                'priority_text' => 'Medium High',
                'description' => 'Great balance of visibility and cost, perfect for growing businesses.',
                'ad_showing_conditions' => ['normal_hours', 'holidays'],
                'cost_per_impression' => 0.0120,
                'is_active' => true,
            ],
            [
                'name' => 'Standard',
                'priority_level' => 45,
                'priority_text' => 'Medium',
                'description' => 'Standard coverage package for regular advertising needs.',
                'ad_showing_conditions' => ['normal_hours'],
                'cost_per_impression' => 0.0080,
                'is_active' => true,
            ],
            [
                'name' => 'Basic',
                'priority_level' => 25,
                'priority_text' => 'Low',
                'description' => 'Budget-friendly option for basic advertising coverage.',
                'ad_showing_conditions' => ['normal_hours'],
                'cost_per_impression' => 0.0050,
                'is_active' => true,
            ],
            [
                'name' => 'Holiday Special',
                'priority_level' => 70,
                'priority_text' => 'High',
                'description' => 'Special package optimized for holiday advertising campaigns.',
                'ad_showing_conditions' => ['holidays'],
                'cost_per_impression' => 0.0200,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
