<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'description',
        'reference_type',
        'reference_id',
        'metadata',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];
    
    /**
     * Create welcome bonus transaction
     */
    public static function createWelcomeBonus($walletId, $userId, $amount, $processedAt)
    {
        return self::create([
            'wallet_id' => $walletId,
            'user_id' => $userId,
            'transaction_id' => self::generateTransactionId(),
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => 0,
            'balance_after' => $amount,
            'status' => 'completed',
            'description' => 'Welcome Bonus',
            'reference_type' => 'welcome_bonus',
            'processed_at' => $processedAt
        ]);
    }

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        return 'TXN' . time() . rand(1000, 9999);
    }
}