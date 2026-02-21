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
}