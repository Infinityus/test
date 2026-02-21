<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publish;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PublishController extends Controller
{
    /**
     * Get all publish records for user
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $publishes = Publish::byUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => [
                'current_page' => $publishes->currentPage(),
                'data' => collect($publishes->items())->map(function ($publish) {
                    return [
                        'id' => $publish->id,
                        'blog_id' => $publish->blog_id,
                        'status' => $publish->status,
                        'status_badge' => $publish->status_badge,
                        'views' => number_format($publish->views),
                        'conversion_rate' => $publish->conversion_rate . '%',
                        'published_at' => $publish->formatted_published_at,
                        'category' => $publish->category,
                        'tags' => $publish->tags,
                    ];
                }),
                'total' => $publishes->total(),
                'per_page' => $publishes->perPage(),
                'last_page' => $publishes->lastPage()
            ]
        ]);
    }

    /**
     * Get single publish record
     */
    public function show($id): JsonResponse
    {
        $publish = Publish::with('user')->find($id);
        
        if (!$publish) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $publish->id,
                'user_id' => $publish->user_id,
                'user_name' => $publish->user?->name,
                'blog_id' => $publish->blog_id,
                'status' => $publish->status,
                'views' => $publish->views,
                'conversion_rate' => $publish->conversion_rate,
                'published_at' => $publish->published_at,
                'scheduled_at' => $publish->scheduled_at,
                'category' => $publish->category,
                'tags' => $publish->tags,
                'description' => $publish->description,
                'created_at' => $publish->created_at,
            ]
        ]);
    }

    /**
     * Store new publish record
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blog_id' => 'required|integer',
            'status' => 'sometimes|in:draft,published,archived,pending,scheduled',
            'category' => 'sometimes|string',
            'tags' => 'sometimes|array',
            'description' => 'sometimes|string',
            'scheduled_at' => 'sometimes|date|after:now',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['views'] = 0;
        $validated['conversion_rate'] = 0;
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }
        
        $publish = Publish::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Publish record created successfully',
            'data' => $publish
        ], 201);
    }

    /**
     * Update publish status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $publish = Publish::find($id);
        
        if (!$publish) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:draft,published,archived,pending,scheduled',
        ]);
        
        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'published') {
            $updateData['published_at'] = now();
        }
        
        $publish->update($updateData);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $publish
        ]);
    }

    /**
     * Increment views
     */
    public function incrementViews($id): JsonResponse
    {
        $publish = Publish::find($id);
        
        if (!$publish) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        
        $publish->incrementViews();
        
        return response()->json([
            'success' => true,
            'message' => 'Views incremented',
            'views' => $publish->views
        ]);
    }

    /**
     * Get analytics summary
     */
    public function analytics(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $totalPublishes = Publish::byUser($user->id)->count();
        $totalViews = Publish::byUser($user->id)->sum('views');
        $avgConversionRate = Publish::byUser($user->id)->avg('conversion_rate');
        $publishedCount = Publish::byUser($user->id)->published()->count();
        $draftCount = Publish::byUser($user->id)->draft()->count();
        
        // Most viewed blogs
        $mostViewed = Publish::byUser($user->id)
            ->mostViewed(5)
            ->get(['blog_id', 'views', 'conversion_rate']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_publishes' => $totalPublishes,
                    'total_views' => number_format($totalViews),
                    'avg_conversion_rate' => round($avgConversionRate, 2) . '%',
                    'published' => $publishedCount,
                    'draft' => $draftCount,
                ],
                'most_viewed' => $mostViewed,
            ]
        ]);
    }
}