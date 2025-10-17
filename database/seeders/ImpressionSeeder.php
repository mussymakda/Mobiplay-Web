<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Impression;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ImpressionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ads = Ad::all();
        $users = User::all();

        if ($ads->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No ads or users found. Please run AdSeeder and UserSeeder first.');

            return;
        }

        $cities = [
            ['city' => 'New York', 'lat' => 40.7128, 'lng' => -74.0060],
            ['city' => 'Los Angeles', 'lat' => 34.0522, 'lng' => -118.2437],
            ['city' => 'Chicago', 'lat' => 41.8781, 'lng' => -87.6298],
            ['city' => 'Houston', 'lat' => 29.7604, 'lng' => -95.3698],
            ['city' => 'Philadelphia', 'lat' => 39.9526, 'lng' => -75.1652],
            ['city' => 'Phoenix', 'lat' => 33.4484, 'lng' => -112.0740],
            ['city' => 'San Antonio', 'lat' => 29.4241, 'lng' => -98.4936],
            ['city' => 'San Diego', 'lat' => 32.7157, 'lng' => -117.1611],
            ['city' => 'Dallas', 'lat' => 32.7767, 'lng' => -96.7970],
            ['city' => 'San Jose', 'lat' => 37.3382, 'lng' => -121.8863],
        ];

        $devices = [
            ['device' => 'mobile', 'os' => 'iOS', 'browser' => 'Safari'],
            ['device' => 'mobile', 'os' => 'Android', 'browser' => 'Chrome'],
            ['device' => 'desktop', 'os' => 'Windows', 'browser' => 'Chrome'],
            ['device' => 'desktop', 'os' => 'macOS', 'browser' => 'Safari'],
            ['device' => 'tablet', 'os' => 'iOS', 'browser' => 'Safari'],
            ['device' => 'tablet', 'os' => 'Android', 'browser' => 'Chrome'],
            ['device' => 'desktop', 'os' => 'Windows', 'browser' => 'Firefox'],
            ['device' => 'desktop', 'os' => 'Linux', 'browser' => 'Firefox'],
        ];

        $userAgents = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Linux; Android 11; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.120 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
        ];

        $ips = [
            '192.168.1.100', '10.0.0.50', '172.16.0.25', '203.0.113.45',
            '198.51.100.78', '192.0.2.123', '203.0.113.89', '198.51.100.234',
            '192.0.2.67', '203.0.113.156',
        ];

        $this->command->info('Creating impressions for each ad...');

        foreach ($ads as $ad) {
            $package = Package::find($ad->package_id);
            $costPerImpression = $package ? $package->cost_per_impression : 0.005;
            $costPerQrScan = $package ? $package->cost_per_qr_scan : 0.10;

            // Generate impressions for the last 30 days
            $totalImpressions = rand(100, 2000);
            $totalQrScans = rand(5, intval($totalImpressions * 0.15)); // 5-15% scan rate

            // Create display impressions
            for ($i = 0; $i < $totalImpressions; $i++) {
                $viewedAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                $city = $cities[array_rand($cities)];
                $device = $devices[array_rand($devices)];
                $userAgent = $userAgents[array_rand($userAgents)];
                $ip = $ips[array_rand($ips)];

                Impression::create([
                    'ad_id' => $ad->id,
                    'user_id' => $ad->user_id,
                    'type' => Impression::TYPE_DISPLAY,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'location_data' => $city,
                    'device_info' => $device,
                    'cost' => $costPerImpression,
                    'viewed_at' => $viewedAt,
                    'created_at' => $viewedAt,
                    'updated_at' => $viewedAt,
                ]);
            }

            // Create QR scan impressions (subset of displays)
            for ($i = 0; $i < $totalQrScans; $i++) {
                $viewedAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                $city = $cities[array_rand($cities)];
                $device = $devices[array_rand($devices)];
                $userAgent = $userAgents[array_rand($userAgents)];
                $ip = $ips[array_rand($ips)];

                Impression::create([
                    'ad_id' => $ad->id,
                    'user_id' => $ad->user_id,
                    'type' => Impression::TYPE_QR_SCAN,
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'location_data' => $city,
                    'device_info' => $device,
                    'cost' => $costPerQrScan,
                    'viewed_at' => $viewedAt,
                    'created_at' => $viewedAt,
                    'updated_at' => $viewedAt,
                ]);
            }

            // Update ad metrics
            $ad->update([
                'impressions' => $totalImpressions,
                'qr_scans' => $totalQrScans,
                'spent' => ($totalImpressions * $costPerImpression) + ($totalQrScans * $costPerQrScan),
            ]);

            $this->command->info("Created {$totalImpressions} impressions and {$totalQrScans} QR scans for ad: {$ad->campaign_name}");
        }

        $this->command->info('Impression seeding completed!');
    }
}
