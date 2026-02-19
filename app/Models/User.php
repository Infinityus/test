<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_user';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'mobile',
        'device_name',
        'token',
        'status'
    ];

    protected $hidden = [
        'token',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'status'      => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    protected $attributes = [
        'status' => 1,
    ];

    /**
     * Find or create a user by mobile number
     * 
     * @param string $mobile
     * @param string $deviceName
     * @return self
     */
    public static function findOrCreateUser(string $mobile, string $deviceName): self
    {
        $now = Carbon::now('Asia/Kolkata');
        
        return self::firstOrCreate(
            ['mobile' => $mobile],
            [
                'name'        => 'User_' . substr($mobile, -6),
                'status'      => 1,
                'device_name' => $deviceName,
                'created_at'  => $now,
                'updated_at'  => $now, // Set updated_at same as created_at for new records
            ]
        );
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->created_at = Carbon::now('Asia/Kolkata');
            $user->updated_at = Carbon::now('Asia/Kolkata');
        });

        static::updating(function ($user) {
            $user->updated_at = Carbon::now('Asia/Kolkata');
        });
    }
    
    public function updateDeviceInfo(?string $deviceName, string $userAgent): void
    {
        $this->update([
            'device_name' => $deviceName ?? $userAgent,
            'updated_at' => Carbon::now('Asia/Kolkata')
        ]);
    }
    
    public function updateToken(string $token): void
    {
        $this->update(['token' => $token]);
    }
    
    /**
     * Get the wallet for the user.
     */
    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }
    
    /**
     * Ensure user has a wallet
     */
    public function getOrCreateWallet()
    {
        if (!$this->wallet) {
            return Wallet::createForUser($this->id);
        }
        return $this->wallet;
    }
}