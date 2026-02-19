<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DeviceHistory extends Model
{
    use HasFactory;

    protected $table = 'tbl_device_history';

    protected $fillable = [
        'user_id',
        'device_name',
        'mobile',
        'ip_address',
        'device_type',
        'device_os',
        'app_version',
        'login_at',
        'action'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if first device history exists
     */
    public static function hasFirstDeviceHistory($user, array $deviceData): bool
    {
        return self::where('user_id', $user->id)
            ->where('mobile', $user->mobile)
            ->where('action', 'first_device')
            ->where('device_name', $deviceData['device_name'])
            ->where('device_type', $deviceData['device_type'])
            ->exists();
    }

    /**
     * Check if device history exists for today
     */
    public static function hasTodayDeviceHistory($user, array $deviceData): bool
    {
        return self::where('user_id', $user->id)
            ->where('mobile', $user->mobile)
            ->where('device_name', $deviceData['device_name'])
            ->whereDate('created_at', Carbon::today('Asia/Kolkata'))
            ->exists();
    }

    /**
     * Check if device change history exists
     */
    public static function hasDeviceChangeHistory($user, array $deviceData): bool
    {
        return self::where('user_id', $user->id)
            ->where('mobile', $user->mobile)
            ->where('action', 'device_change')
            ->where('device_name', $deviceData['device_name'])
            ->where('device_type', $deviceData['device_type'])
            ->exists();
    }

    /**
     * Log device history
     */
    public static function logDeviceHistory($user, array $deviceData, string $action): self
    {
        return self::create([
            'user_id' => $user->id,
            'device_name' => $deviceData['device_name'],
            'mobile' => $user->mobile,
            'ip_address' => request()->ip(),
            'device_type' => $deviceData['device_type'],
            'device_os' => $deviceData['device_os'],
            'app_version' => $deviceData['app_version'],
            'login_at' => now(),
            'action' => $action
        ]);
    }

    /**
     * Get device history by user
     */
    public static function getUserHistory($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get device history by device
     */
    public static function getDeviceHistory($deviceName)
    {
        return self::where('device_name', $deviceName)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get action display name
     */
    public function getActionDisplayAttribute(): string
    {
        return match($this->action) {
            'first_device' => 'First Device Login',
            'existing_device' => 'Existing Device Login',
            'device_change' => 'Device Changed',
            default => ucfirst(str_replace('_', ' ', $this->action))
        };
    }
}