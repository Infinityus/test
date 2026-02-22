<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publish extends Model
{
    use HasFactory;

    protected $table = 'tbl_publish';

    protected $fillable = [
        'user_id',
        'blog_id',
        'status',
        'views',
        'actions',
        'conversion_rate',
        'published_at',
        'scheduled_at',
        'category',
        'tags',
        'description'
    ];

    protected $casts = [
        'views' => 'integer',
        'conversion_rate' => 'decimal:2',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Status constants
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';

    /**
     * Get the user that owns the publish record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get records by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get records by blog
     */
    public function scopeByBlog($query, $blogId)
    {
        return $query->where('blog_id', $blogId);
    }

    /**
     * Scope to get by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get published records
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Scope to get draft records
     */
    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    /**
     * Scope to get most viewed
     */
    public function scopeMostViewed($query, $limit = 10)
    {
        return $query->orderBy('views', 'desc')->limit($limit);
    }

    /**
     * Scope to get by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('published_at', [$startDate, $endDate]);
    }

    /**
     * Increment views
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Calculate conversion rate
     */
    public function calculateConversionRate($actions, $views): float
    {
        if ($views == 0) return 0;
        return round(($actions / $views) * 100, 2);
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            self::STATUS_DRAFT => '<span class="badge bg-secondary">Draft</span>',
            self::STATUS_PUBLISHED => '<span class="badge bg-success">Published</span>',
            self::STATUS_ARCHIVED => '<span class="badge bg-danger">Archived</span>',
            self::STATUS_PENDING => '<span class="badge bg-warning">Pending</span>',
            self::STATUS_SCHEDULED => '<span class="badge bg-info">Scheduled</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-light">Unknown</span>';
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedAtAttribute(): string
    {
        return $this->published_at 
            ? $this->published_at->format('d M Y, h:i A') 
            : 'Not published';
    }

    /**
     * Check if scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    /**
     * Check if published
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}