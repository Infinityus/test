<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppApi extends Model
{
    use HasFactory;

    protected $table = 'tbl_whatsapp_api';

    protected $fillable = [
        'user',
        'pass',
        'sender',
        'text',
        'priority',
        'stype',
        'is_active',
        'description'
    ];

    protected $hidden = [
        'pass', // Hide password in JSON responses
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active WhatsApp API configuration for OTP
     */
    public static function getActiveOtpConfig()
    {
        return self::where('stype', 'auth')
            ->where('text', 'otp_verify')
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get active WhatsApp API configuration by type
     */
    public static function getActiveByType(string $stype, string $text = null)
    {
        $query = self::where('stype', $stype)
            ->where('is_active', true);
        
        if ($text) {
            $query->where('text', $text);
        }
        
        return $query->first();
    }
}