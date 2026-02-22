<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Earning extends Model
{
    use HasFactory;

    protected $table = 'tbl_earnings';

    protected $fillable = [
        'user_id',
        'blog_id',
        'blog_name',
        'blog_status',
        'amount',
        'currency',
        'transaction_id',
        'earned_at',
        'paid_at',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'earned_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('blog_status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('blog_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('blog_status', 'paid');
    }

    public function scopeLastDays($query, $days)
    {
        return $query->where('earned_at', '>=', now()->subDays($days));
    }

    public function scopeLast7Days($query)
    {
        return $query->where('earned_at', '>=', now()->subDays(7));
    }

    public function scopeLast30Days($query)
    {
        return $query->where('earned_at', '>=', now()->subDays(30));
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('earned_at', now()->month)
                     ->whereYear('earned_at', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('earned_at', now()->subMonth()->month)
                     ->whereYear('earned_at', now()->subMonth()->year);
    }
}