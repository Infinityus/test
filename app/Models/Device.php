<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Device extends Model
{
    use HasFactory;

    protected $table = 'tbl_device';

    protected $fillable = [
        'user_id',
        'device_name',
        'mobile',
        'token',
        'ip_address',
        'device_type',
        'device_os',
        'app_version',
        'last_used_at',
        'waiting_time'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'waiting_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserDevices(int $userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function findActiveWaitingDevice(int $userId): ?self
    {
        return self::where('user_id', $userId)
            ->where('waiting_time', '>', Carbon::now('Asia/Kolkata'))
            ->orderBy('waiting_time', 'desc')
            ->first();
    }

    public static function findDeviceByName(int $userId, string $deviceName): ?self
    {
        return self::where('user_id', $userId)
            ->where('device_name', $deviceName)
            ->first();
    }

    public static function createFirstDevice($user, array $data, string $token): self
    {
        return self::create([
            'user_id' => $user->id,
            'device_name' => $data['device_name'],
            'mobile' => $user->mobile,
            'token' => $token,
            'ip_address' => $data['ip_address'],
            'device_type' => $data['device_type'],
            'device_os' => $data['device_os'],
            'app_version' => $data['app_version'],
            'last_used_at' => Carbon::now('Asia/Kolkata'),
            'waiting_time' => null,
        ]);
    }

    public function updateDeviceInfo(array $data, string $token): void
    {
        $this->update([
            'token' => $token,
            'ip_address' => $data['ip_address'],
            'device_type' => $data['device_type'],
            'device_os' => $data['device_os'],
            'app_version' => $data['app_version'],
            'last_used_at' => Carbon::now('Asia/Kolkata'),
            'updated_at' => Carbon::now('Asia/Kolkata'),
            'waiting_time' => null,
        ]);
    }

    public function setWaitingPeriod(int $hours = 24): void
    {
        $this->update([
            'waiting_time' => Carbon::now('Asia/Kolkata')->addHours($hours)
        ]);
    }

    /**
     * Delete expired waiting time records
     */
    public static function deleteExpiredWaitingTime(int $userId): int
    {
        return self::where('user_id', $userId)
            ->where('waiting_time', '<=', now())
            ->delete();
    }
    
    /**
     * Check and clean up expired waiting time before login
     */
   public static function cleanupExpiredWaitingTime(int $userId): void
    {
        self::where('user_id', $userId)
            ->where('waiting_time', '<=', now())
            ->whereNotNull('waiting_time')
            ->update(['waiting_time' => null]);
    }
}