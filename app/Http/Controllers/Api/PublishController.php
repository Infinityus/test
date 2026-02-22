<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publish;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class PublishController extends Controller
{
    /**
     * Get publish analytics dashboard data
     */
    public function analytics(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();

        // 1. Total published count
        $totalPublished = Publish::byUser($user->id)->where('status', 'published')->count();

        // 2. This week published count
        $thisWeekPublished = Publish::byUser($user->id)->where('status', 'published')->where('published_at', '>=', $thisWeekStart)->count();

        // 3. Total views
        $totalViews = Publish::byUser($user->id)->where('status', 'published')->sum('views');

        // 4. Yesterday's views vs today's views (for percentage)
        $viewsYesterday = Publish::byUser($user->id)->where('status', 'published')->whereDate('published_at', $yesterday)->sum('views');
            
        $viewsToday = Publish::byUser($user->id)->where('status', 'published')->whereDate('published_at', $today)->sum('views');
            
        // $viewsPercentageChange = $this->calculatePercentageChange($viewsYesterday, $viewsToday);

        // 5. Average conversion rate
        $avgConversionRate = Publish::byUser($user->id)->where('status', 'published')->where('views', '>', 0)->avg('conversion_rate');
            
        $avgConversionRate = $avgConversionRate ? round($avgConversionRate, 1) : 0;

        // 6. This week conversion rate
        $thisWeekConversionRate = Publish::byUser($user->id)->where('status', 'published')->where('published_at', '>=', $thisWeekStart)->where('views', '>', 0)->avg('conversion_rate');
            
        $thisWeekConversionRate = $thisWeekConversionRate ? round($thisWeekConversionRate, 1) : 0;

        // 7. Yesterday's conversion rate
        $yesterdayConversionRate = Publish::byUser($user->id)->where('status', 'published')->whereDate('published_at', $yesterday)->where('views', '>', 0)->avg('conversion_rate');
            
        $yesterdayConversionRate = $yesterdayConversionRate ? round($yesterdayConversionRate, 1) : 0;

        // 8. This week vs last week comparison (for published count)
        $lastWeekPublished = Publish::byUser($user->id)->where('status', 'published')->whereBetween('published_at', [$lastWeekStart, $lastWeekEnd])->count();
            
        //$publishedVsLastWeek = $this->calculateVsLastWeek($thisWeekPublished, $lastWeekPublished);

        return response()->json([
            'success' => true,
            'data' => [
                'total_published' => [
                    'count' => $totalPublished,
                    'this_week' => $thisWeekPublished
                    //'vs_last_week' => $publishedVsLastWeek
                ],
                'total_views' => [
                    'count' => $this->formatNumber($totalViews),
                    'today_views' => $viewsToday,
                    'today_views_percentage' => $this->calculateViewsPercentage($viewsToday, $totalViews)
                    //'today_vs_yesterday' => $viewsPercentageChange,
                    //'raw' => $totalViews
                ],
                'conversion_rate' => [
                    'average' => $avgConversionRate . '%',
                    'this_week' => $thisWeekConversionRate . '%',
                    //'yesterday' => $yesterdayConversionRate . '%',
                    //'trend' => $this->getConversionTrend($thisWeekConversionRate, $avgConversionRate)
                ]
                /*'summary' => [
                    'published_count' => $totalPublished,
                    'total_views_k' => round($totalViews / 1000, 1) . 'k',
                    'avg_conv_rate' => $avgConversionRate . '%',
                    'weekly_change' => $publishedVsLastWeek['percentage'],
                    'weekly_direction' => $publishedVsLastWeek['direction'],
                    'weekly_count' => '+' . $thisWeekPublished . ' this week',
                    'yesterday_conv' => ($yesterdayConversionRate > 0 ? '↑' : '↓') . abs($yesterdayConversionRate) . '%'
                ]*/
            ]
        ]);
    }

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue): array
    {
        if ($oldValue == 0) {
            return [
                'percentage' => $newValue > 0 ? '+100' : '0',
                'direction' => $newValue > 0 ? 'up' : 'neutral'
            ];
        }
        
        $change = (($newValue - $oldValue) / $oldValue) * 100;
        $rounded = round($change, 1);
        
        return [
            'percentage' => ($rounded > 0 ? '+' : '') . $rounded,
            'direction' => $rounded > 0 ? 'up' : ($rounded < 0 ? 'down' : 'neutral')
        ];
    }

    /**
     * Calculate vs last week percentage
     */
    private function calculateVsLastWeek($thisWeek, $lastWeek): array
    {
        if ($lastWeek == 0) {
            return [
                'percentage' => $thisWeek > 0 ? '+100' : '0',
                'direction' => $thisWeek > 0 ? 'up' : 'neutral'
            ];
        }
        
        $change = (($thisWeek - $lastWeek) / $lastWeek) * 100;
        $rounded = round($change, 1);
        
        return [
            'percentage' => ($rounded > 0 ? '+' : '') . $rounded . '%',
            'direction' => $rounded > 0 ? 'up' : ($rounded < 0 ? 'down' : 'neutral')
        ];
    }

    /**
     * Get conversion rate trend
     */
    private function getConversionTrend($current, $average): string
    {
        if ($current > $average) {
            return '↑ higher than average';
        } elseif ($current < $average) {
            return '↓ lower than average';
        } else {
            return '→ same as average';
        }
    }

    /**
     * Format number with K/M suffix
     */
    private function formatNumber($number): string
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, 1) . 'k';
        }
        return (string) $number;
    }
    
    /**
     * Get recent blog activity for dashboard
     */
    public function recentActivity(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get the most recent published blog
        $recentBlog = Publish::byUser($user->id)->where('status', 'published')->orderBy('published_at', 'desc')->first();
        
        // If no published blogs found
        if (!$recentBlog) {
            return response()->json([
                'success' => true,
                'message' => 'No published blogs yet',
                'data' => [
                    'has_activity' => false,
                    'empty_state' => [
                        'title' => 'Start Your Journey',
                        'message' => 'Publish your first blog to see activity here'
                    ]
                ]
            ]);
        }
        
        // Get earnings for this blog from tbl_earnings
        $blogEarning = \App\Models\Earning::where('user_id', $user->id)->where('blog_id', $recentBlog->blog_id)->where('blog_status', 'paid')->latest('earned_at')->first();
        
        $earningsAmount = $blogEarning ? $blogEarning->amount : 0;
        
        // Format the blog title (limit to 20 chars for display)
        $blogTitle = $blogEarning->blog_name ?? $recentBlog->blog_name ?? 'Untitled Blog';
        $shortTitle = strlen($blogTitle) > 20 ? substr($blogTitle, 0, 20) . '...' : $blogTitle;
        
        // Format date
        $publishedDate = $recentBlog->published_at;
        $formattedDate = $publishedDate?->isToday() 
            ? 'Today, ' . $publishedDate->format('h:i A')
            : ($publishedDate?->isYesterday() 
                ? 'Yesterday, ' . $publishedDate->format('h:i A')
                : $publishedDate?->format('M d, Y h:i A'));
        
        return response()->json([
            'success' => true,
            'message' => 'Recent activity retrieved',
            'data' => [
                'has_activity' => true,
                'recent_blog' => [
                    //'id' => $recentBlog->id,
                    'title' => $blogTitle,
                    'short_title' => $shortTitle,
                    'status' => $recentBlog->status,
                    //'published_at' => $recentBlog->published_at,
                    'formatted_date' => $formattedDate,
                    'time_ago' => $publishedDate?->diffForHumans(),
                    'earnings' => $earningsAmount > 0 ? '¥' . number_format($earningsAmount) : null,
                    'earning_amount' => $earningsAmount,
                    'currency' => $blogEarning->currency ?? 'INR'
                ]
            ]
        ]);
    }
    
    /**
     * Calculate what percentage today's views represent of total views
     */
    private function calculateViewsPercentage($todayViews, $totalViews): string
    {
        if ($totalViews == 0) {
            return '0%';
        }
        
        $percentage = ($todayViews / $totalViews) * 100;
        return round($percentage, 1) . '%';
    }
}