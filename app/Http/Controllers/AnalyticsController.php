<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Impression;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's active campaigns
        $campaigns = Ad::where('user_id', $user->id)
            ->whereIn('status', ['active', 'completed'])
            ->select('id', 'campaign_name', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Date range handling
        $startDate = $request->input('start_date', Carbon::now()->subDays(29)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $campaignId = $request->input('campaign_id');

        // Base query for user's ads
        $adsQuery = Ad::where('user_id', $user->id);

        if ($campaignId) {
            $adsQuery->where('id', $campaignId);
        }

        $userAds = $adsQuery->pluck('id');

        // Get analytics data
        $analytics = $this->getAnalyticsData($userAds, $startDate, $endDate);

        return view('analytics', compact('campaigns', 'analytics', 'startDate', 'endDate', 'campaignId'));
    }

    private function getAnalyticsData($adIds, $startDate, $endDate)
    {
        // Total impressions
        $totalImpressions = Impression::whereIn('ad_id', $adIds)
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->count();

        // Total spend
        $totalSpent = Ad::whereIn('id', $adIds)->sum('spent');

        // QR scans (from ads table)
        $totalQrScans = Ad::whereIn('id', $adIds)->sum('qr_scans');

        // Calculate CTR (QR scans / impressions)
        $ctr = $totalImpressions > 0 ? round(($totalQrScans / $totalImpressions) * 100, 2) : 0;

        // Calculate CPM (cost per mille impressions)
        $cpm = $totalImpressions > 0 ? round(($totalSpent / $totalImpressions) * 1000, 2) : 0;

        // Daily impressions for chart
        $dailyImpressions = Impression::whereIn('ad_id', $adIds)
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Daily spend for chart (based on impression costs)
        $dailySpend = Impression::whereIn('ad_id', $adIds)
            ->whereBetween('viewed_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('SUM(cost) as total_cost'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total_cost', 'date')
            ->toArray();

        return [
            'total_impressions' => $totalImpressions,
            'total_spent' => $totalSpent,
            'total_qr_scans' => $totalQrScans,
            'ctr' => $ctr,
            'cpm' => $cpm,
            'daily_impressions' => $dailyImpressions,
            'daily_spend' => $dailySpend,
        ];
    }
}
