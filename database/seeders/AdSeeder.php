<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $packages = Package::all();

        if ($users->isEmpty() || $packages->isEmpty()) {
            $this->command->warn('No users or packages found. Please run UserSeeder and PackageSeeder first.');

            return;
        }

        $ads = [
            [
                'user_id' => $users->first()->id,
                'package_id' => $packages->first()->id,
                'campaign_name' => 'Summer Sale 2025',
                'media_type' => Ad::MEDIA_TYPE_IMAGE,
                'media_path' => 'ads/summer-sale-banner.jpg',
                'cta_url' => 'https://example.com/summer-sale',
                'qr_code_url' => 'https://example.com/qr/summer-sale',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'location_name' => 'Times Square, NYC',
                'radius_miles' => 6.2, // Converted from 10km to miles
                'status' => Ad::STATUS_ACTIVE,
                'budget' => 500.00,
                'spent' => 127.50,
                'impressions' => 15420,
                'qr_scans' => 287,
            ],
            [
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'package_id' => $packages->skip(1)->first()->id ?? $packages->first()->id,
                'campaign_name' => 'New Restaurant Opening',
                'media_type' => Ad::MEDIA_TYPE_VIDEO,
                'media_path' => 'ads/restaurant-promo.mp4',
                'cta_url' => 'https://example.com/restaurant-menu',
                'qr_code_url' => 'https://example.com/qr/restaurant',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'location_name' => 'Downtown LA',
                'radius_miles' => 9.3, // Converted from 15km to miles
                'status' => Ad::STATUS_ACTIVE,
                'budget' => 750.00,
                'spent' => 89.25,
                'impressions' => 8930,
                'qr_scans' => 156,
            ],
            [
                'user_id' => $users->first()->id,
                'package_id' => $packages->skip(2)->first()->id ?? $packages->first()->id,
                'campaign_name' => 'Black Friday Preview',
                'media_type' => Ad::MEDIA_TYPE_IMAGE,
                'media_path' => 'ads/black-friday-preview.jpg',
                'cta_url' => 'https://example.com/black-friday',
                'qr_code_url' => 'https://example.com/qr/black-friday',
                'latitude' => 41.8781,
                'longitude' => -87.6298,
                'location_name' => 'Chicago Loop',
                'radius_miles' => 5.0, // Converted from 8km to miles
                'status' => Ad::STATUS_PENDING,
                'budget' => 1000.00,
                'spent' => 0.00,
                'impressions' => 0,
                'qr_scans' => 0,
            ],
            [
                'user_id' => $users->skip(2)->first()->id ?? $users->first()->id,
                'package_id' => $packages->first()->id,
                'campaign_name' => 'Fitness Center Promotion',
                'media_type' => Ad::MEDIA_TYPE_VIDEO,
                'media_path' => 'ads/fitness-promo.mp4',
                'cta_url' => 'https://example.com/gym-membership',
                'qr_code_url' => 'https://example.com/qr/fitness',
                'latitude' => 29.7604,
                'longitude' => -95.3698,
                'location_name' => 'Houston Downtown',
                'radius_miles' => 7.5, // Converted from 12km to miles
                'status' => Ad::STATUS_PAUSED,
                'budget' => 300.00,
                'spent' => 245.80,
                'impressions' => 12340,
                'qr_scans' => 98,
            ],
            [
                'user_id' => $users->first()->id,
                'package_id' => $packages->skip(1)->first()->id ?? $packages->first()->id,
                'campaign_name' => 'Coffee Shop Grand Opening',
                'media_type' => Ad::MEDIA_TYPE_IMAGE,
                'media_path' => 'ads/coffee-shop-opening.jpg',
                'cta_url' => 'https://example.com/coffee-menu',
                'qr_code_url' => 'https://example.com/qr/coffee',
                'latitude' => 47.6062,
                'longitude' => -122.3321,
                'location_name' => 'Seattle Center',
                'radius_miles' => 3.7, // Converted from 6km to miles
                'status' => Ad::STATUS_COMPLETED,
                'budget' => 200.00,
                'spent' => 200.00,
                'impressions' => 9876,
                'qr_scans' => 234,
            ],
            [
                'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                'package_id' => $packages->first()->id,
                'campaign_name' => 'Tech Startup Launch',
                'media_type' => Ad::MEDIA_TYPE_VIDEO,
                'media_path' => 'ads/tech-startup.mp4',
                'cta_url' => 'https://example.com/startup-demo',
                'qr_code_url' => 'https://example.com/qr/startup',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
                'location_name' => 'San Francisco Bay Area',
                'radius_miles' => 12.4, // Converted from 20km to miles
                'status' => Ad::STATUS_ACTIVE,
                'budget' => 1500.00,
                'spent' => 345.67,
                'impressions' => 18750,
                'qr_scans' => 412,
            ],
        ];

        foreach ($ads as $adData) {
            Ad::create($adData);
        }

        $this->command->info('Created '.count($ads).' sample ads');
    }
}
