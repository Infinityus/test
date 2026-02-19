<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'device_name',
        'token',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'token',           // hide sensitive token in JSON responses
        // 'password',     // add this later if you implement password login
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'status'      => 'boolean',     // 0/1 → false/true (optional but clean)
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
            // Add these later if you add email/password:
            // 'email_verified_at' => 'datetime',
            // 'password'          => 'hashed',
        ];
    }

    /**
     * Optional: Default attribute values
     */
    protected $attributes = [
        'status' => 1,
    ];
}
