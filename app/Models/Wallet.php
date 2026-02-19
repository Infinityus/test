<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'tbl_wallet';

    protected $fillable = [
        'user_id',
        'life_time_earing', // Changed from 'balance'
        'total_earned',
        'total_withdrawn',
        'pending_withdrawal',
        'available_balance',
        'currency'
    ];

    protected $casts = [
        'life_time_earing' => 'decimal:2', // Changed from 'balance'
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'pending_withdrawal' => 'decimal:2',
        'available_balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'wallet_id');
    }

    /**
     * Get or create wallet for user
     */
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'life_time_earing' => 0.00, // Changed from 'balance'
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
                'pending_withdrawal' => 0.00,
                'available_balance' => 0.00,
                'currency' => 'INR' // Changed to INR
            ]
        );
    }

    /**
     * Get formatted wallet data for API response
     */
    public function getFormattedData(): array
    {
        return [
            'balance' => [
                'total' => number_format($this->life_time_earing, 2), // Changed from 'balance'
                'available' => number_format($this->available_balance, 2),
                'pending' => number_format($this->pending_withdrawal, 2),
                'currency' => $this->currency
            ],
            'totals' => [
                'total_earned' => number_format($this->total_earned, 2),
                'total_withdrawn' => number_format($this->total_withdrawn, 2),
            ],
            'stats' => [
                'this_month' => $this->getThisMonthEarnings(),
                'monthly_change' => $this->getMonthlyChange(),
            ]
        ];
    }

    /**
     * Get this month's earnings
     */
    public function getThisMonthEarnings(): float
    {
        return $this->transactions()
            ->where('type', 'credit')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }

    /**
     * Get monthly change percentage
     */
    public function getMonthlyChange(): string
    {
        $lastMonth = $this->transactions()
            ->where('type', 'credit')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount');
        
        $thisMonth = $this->getThisMonthEarnings();
        
        if ($lastMonth == 0) {
            return $thisMonth > 0 ? '+100%' : '0%';
        }
        
        $change = (($thisMonth - $lastMonth) / $lastMonth) * 100;
        return ($change > 0 ? '+' : '') . number_format($change, 1) . '%';
    }
}