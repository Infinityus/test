<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'tbl_banks';

    protected $fillable = [
        'user_id',
        'mobile',
        'bank_name',
        'ifsc',
        'upi',
        'account_name',
        'account_number',
        'branch_name',
        'account_type',
        //'is_primary',
        //'is_verified',
        'status',
        'metadata'
    ];

    protected $casts = [
        //'is_primary' => 'boolean',
        //'is_verified' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the bank account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get banks by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get banks by mobile
     */
    public function scopeByMobile($query, $mobile)
    {
        return $query->where('mobile', $mobile);
    }

    /**
     * Scope to get active banks
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get formatted IFSC
     */
    public function getFormattedIfscAttribute(): string
    {
        return strtoupper($this->ifsc);
    }

    /**
     * Get masked account number
     */
    public function getMaskedAccountAttribute(): string
    {
        if (!$this->account_number) return '';
        return 'XXXXXX' . substr($this->account_number, -4);
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'inactive' => '<span class="badge bg-warning">Inactive</span>',
            'blocked' => '<span class="badge bg-danger">Blocked</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }
}