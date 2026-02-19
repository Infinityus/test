<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'tbl_otp';

    protected $fillable = [
        'user_id',
        'mobile',
        'otp',
        'expiry_time',
        'ip_address',
        'rate_limit',
        'set_limit',
    ];

    protected $casts = [
        'expiry_time' => 'datetime',
        'rate_limit'  => 'integer',
        'set_limit'   => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isExpired(): bool
    {
        return $this->expiry_time->isPast();
    }

    public static function getTodayOtpRecord(string $mobile): ?self
    {
        $todayStart = Carbon::today('Asia/Kolkata')->startOfDay();
        
        return self::where('mobile', $mobile)
            ->where('created_at', '>=', $todayStart)
            ->orderBy('created_at', 'desc')
            ->first(['id', 'rate_limit', 'set_limit', 'updated_at']);
    }

    public static function findValidOtp(string $mobile, string $otp): ?self
    {
        return self::where('mobile', $mobile)
            ->where('otp', $otp)
            ->where('expiry_time', '>', Carbon::now('Asia/Kolkata'))
            ->latest()
            ->first();
    }

    public function incrementRateLimit(string $otpCode, Carbon $expiry, string $ip): void
    {
        $this->update([
            'rate_limit'  => $this->rate_limit + 1,
            'updated_at'  => Carbon::now('Asia/Kolkata'),
            'otp'         => $otpCode,
            'expiry_time' => $expiry,
            'ip_address'  => $ip,
        ]);
    }

    public static function createNewOtp($userId, string $mobile, string $otpCode, Carbon $expiry, string $ip, int $rateLimit = 1): self
    {
        return self::create([
            'user_id'     => $userId,
            'mobile'      => $mobile,
            'otp'         => $otpCode,
            'expiry_time' => $expiry,
            'ip_address'  => $ip,
            'rate_limit'  => $rateLimit
            //'set_limit'   => 5,
        ]);
    }
}