<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AutoBlog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AutoBlogController extends Controller
{
    /**
     * Get next pending product/blog for user
     */
    public function nextPending(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user->id;

        // Check if user already has a pending blog
        $userPendingBlog = AutoBlog::forUser($userId)->pending()->first();

        if ($userPendingBlog) {
            return response()->json([
                'success' => true,
                'message' => 'You have a pending blog to work on',
                'data' => $this->formatBlogData($userPendingBlog)
            ]);
        }

        // Get next available unassigned blog
        $nextBlog = AutoBlog::whereNull('user_id')->pending()->orderBy('id', 'asc')->first();

        if ($nextBlog) {
            // Assign this blog to the user
            $nextBlog->update([
                'user_id' => $userId,
                'last_updated_date' => now()
            ]);

            // Refresh to get updated data
            $nextBlog = $nextBlog->fresh();

            return response()->json([
                'success' => true,
                'message' => 'New blog assigned successfully',
                'data' => $this->formatBlogData($nextBlog)
            ]);
        }

        // No blogs available
        return response()->json([
            'success' => false,
            'message' => 'Currently no new blogs or products available. Please check back later.',
            'data' => null
        ], 404);
    }

    /**
     * Get specific blog by ID
     */
    public function show($id): JsonResponse
    {
        $blog = AutoBlog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatBlogData($blog)
        ]);
    }

    /**
     * Update blog status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'data_submitted' => 'required|in:yes,no,failed'
        ]);

        $blog = AutoBlog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->update([
            'data_submitted' => $request->data_submitted,
            'last_updated_date' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog status updated successfully',
            'data' => $this->formatBlogData($blog)
        ]);
    }

    /**
     * Get user's blog history
     */
    public function userHistory(Request $request): JsonResponse
    {
        $user = $request->user();

        $blogs = AutoBlog::forUser($user->id)
            ->orderBy('created_date', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $blogs->currentPage(),
                'data' => collect($blogs->items())->map(function ($blog) {
                    return $this->formatBlogData($blog);
                }),
                'total' => $blogs->total(),
                'per_page' => $blogs->perPage(),
                'last_page' => $blogs->lastPage()
            ]
        ]);
    }

    /**
     * Format blog data for response
     */
    private function formatBlogData($blog): array
    {
        return [
            'id' => (string) $blog->id,
            'user_id' => $blog->user_id,
            'asin' => $blog->asin,
            'product_title' => $blog->product_title,
            'post_title' => $blog->post_title,
            'brand' => $blog->brand,
            'product_category' => $blog->product_category,
            'product_tags' => $blog->product_tags,
            'short_description' => $blog->short_description,
            'key_features' => $blog->key_features,
            'technical_specifications' => $blog->technical_specifications,
            'average_rating' => $blog->average_rating,
            'review_count' => (string) $blog->review_count,
            'pros' => $blog->pros,
            'cons' => $blog->cons,
            'who_should_buy' => $blog->who_should_buy,
            'who_should_avoid' => $blog->who_should_avoid,
            'primary_use_cases' => $blog->primary_use_cases,
            'performance_overview' => $blog->performance_overview,
            'build_quality_design' => $blog->build_quality_design,
            'ease_of_use_setup' => $blog->ease_of_use_setup,
            'battery_life_power' => $blog->battery_life_power,
            'connectivity_compatibility' => $blog->connectivity_compatibility,
            'comparison_with_similar' => $blog->comparison_with_similar,
            'value_for_money' => $blog->value_for_money,
            'buying_guide' => $blog->buying_guide,
            'warranty_support' => $blog->warranty_support,
            'faq' => $blog->faq,
            'final_verdict' => $blog->final_verdict,
            'affiliate_product_link' => $blog->affiliate_product_link,
            'affiliate_disclosure' => $blog->affiliate_disclosure,
            'last_updated_date' => $blog->last_updated_date,
            'created_date' => $blog->created_date,
            'is_active' => (string) $blog->is_active,
            'data_submitted' => $blog->data_submitted
        ];
    }
}