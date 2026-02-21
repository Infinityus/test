<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoBlog extends Model
{
    use HasFactory;

    protected $table = 'tbl_auto_blog';

    protected $primaryKey = 'id';

    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'last_updated_date';

    protected $fillable = [
        'user_id',
        'asin',
        'product_title',
        'post_title',
        'brand',
        'product_category',
        'product_tags',
        'short_description',
        'key_features',
        'technical_specifications',
        'average_rating',
        'review_count',
        'pros',
        'cons',
        'who_should_buy',
        'who_should_avoid',
        'primary_use_cases',
        'performance_overview',
        'build_quality_design',
        'ease_of_use_setup',
        'battery_life_power',
        'connectivity_compatibility',
        'comparison_with_similar',
        'value_for_money',
        'buying_guide',
        'warranty_support',
        'faq',
        'final_verdict',
        'affiliate_product_link',
        'affiliate_disclosure',
        'is_active',
        'data_submitted'
    ];

    protected $casts = [
        'average_rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_active' => 'boolean',
        'created_date' => 'datetime',
        'last_updated_date' => 'datetime'
    ];

    /**
     * Get pending blog for user or assign new one
     */
    public static function getNextPendingForUser($userId)
    {
        // Check if user already has a pending blog
        $userPendingBlog = self::where('user_id', $userId)
            ->where('data_submitted', 'no')
            ->first();
        
        if ($userPendingBlog) {
            return $userPendingBlog;
        }
        
        // Get next available unassigned blog
        $nextBlog = self::whereNull('user_id')
            ->where('data_submitted', 'no')
            ->orderBy('id', 'asc')
            ->first();
        
        if ($nextBlog) {
            // Assign this blog to the user
            $nextBlog->update([
                'user_id' => $userId,
                'last_updated_date' => now()
            ]);
            
            return $nextBlog;
        }
        
        return null;
    }

    /**
     * Scope for pending blogs
     */
    public function scopePending($query)
    {
        return $query->where('data_submitted', 'no');
    }

    /**
     * Scope for completed blogs
     */
    public function scopeCompleted($query)
    {
        return $query->where('data_submitted', 'yes');
    }

    /**
     * Scope for failed blogs
     */
    public function scopeFailed($query)
    {
        return $query->where('data_submitted', 'failed');
    }

    /**
     * Scope for user's blogs
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}