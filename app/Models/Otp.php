<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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
    ];

    protected $casts = [
        'expiry_time' => 'datetime',
        'rate_limit'  => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isExpired(): bool
    {
        return $this->expiry_time->isPast();
    }
}
