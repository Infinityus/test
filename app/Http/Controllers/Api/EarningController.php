<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EarningController extends Controller
{
    /**
     * Get earnings summary for last 7 days
     */
    public function last7Days(Request $request): JsonResponse
    {
        return $this->getEarningsChartData($request->user()->id, 7, 'Last 7 Days');
    }

    /**
     * Get earnings summary for last 15 days
     */
    public function last15Days(Request $request): JsonResponse
    {
        return $this->getEarningsChartData($request->user()->id, 15, 'Last 15 Days');
    }

    /**
     * Get earnings summary for last 30 days
     */
    public function last30Days(Request $request): JsonResponse
    {
        return $this->getEarningsChartData($request->user()->id, 30, 'Last 30 Days');
    }
    
    /**
     * Get earnings summary for last 60 days
     */
    public function last60Days(Request $request): JsonResponse
    {
        return $this->getEarningsChartData($request->user()->id, 60, 'Last 60 Days');
    }

    /**
     * Helper method to get earnings chart data by period
     */
    private function getEarningsChartData($userId, $days, $periodName): JsonResponse
    {
        $startDate = now()->subDays($days)->startOfDay();
        
        $earnings = Earning::byUser($userId)
            ->where('blog_status', 'paid')
            ->where('earned_at', '>=', $startDate)
            ->orderBy('earned_at', 'asc')
            ->get();
        
        // Calculate total earnings for percentage calculations
        $totalEarnings = $earnings->sum('amount');
        
        // Group by date for chart with percentages
        $dailyBreakdown = $earnings->groupBy(function($item) {
            return $item->earned_at?->format('Y-m-d');
        })->map(function($day) use ($totalEarnings) {
            $dailyTotal = $day->sum('amount');
            $percentage = $totalEarnings > 0 
                ? round(($dailyTotal / $totalEarnings) * 100, 1) 
                : 0;
            
            return [
                'date' => $day->first()->earned_at?->format('M d, Y'),
                'short_date' => $day->first()->earned_at?->format('M d'),
                'amount' => $dailyTotal,
                'formatted_amount' => $this->formatAmount($dailyTotal),
                'percentage' => $percentage . '%',
                'count' => $day->count()
            ];
        })->values();
        
        // If no earnings data
        if ($dailyBreakdown->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No earnings data available for this period',
                'data' => [
                    'period' => $periodName,
                    'total_earnings' => '0',
                    'total_days' => $days,
                    'chart' => [
                        'labels' => [],
                        'data' => [],
                        'percentages' => []
                    ]
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'period' => $periodName,
                'total_earnings' => $this->formatAmount($totalEarnings),
                'total_days' => $days,
                'days_with_earnings' => $dailyBreakdown->count(),
                'chart' => [
                    'labels' => $dailyBreakdown->pluck('short_date'),
                    'amounts' => $dailyBreakdown->pluck('amount'),
                    'formatted_amounts' => $dailyBreakdown->pluck('formatted_amount'),
                    'percentages' => $dailyBreakdown->pluck('percentage'),
                    'detailed' => $dailyBreakdown
                ]
            ]
        ]);
    }

    /**
     * Format amount without .00 if whole number
     */
    private function formatAmount($amount): string
    {
        if ($amount == 0) return '0';
        
        $formatted = number_format($amount, 2);
        if (substr($formatted, -3) === '.00') {
            return substr($formatted, 0, -3);
        }
        return $formatted;
    }
}