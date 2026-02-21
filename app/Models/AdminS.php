<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens; // Add this
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminS extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // Add HasApiTokens

    protected $table = 'tbl_admin-s';

    protected $fillable = [
        'admin_name',
        'role',
        'ip_address',
        'token',
        'status',
        'mobile',
        'gmail',
        'last_login'
    ];

    protected $hidden = [
        'token',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'last_login' => 'datetime',
        'role' => 'integer'
    ];

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_EMPLOYEE = 3;

    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_EMPLOYEE => 'Employee',
            default => 'Unknown'
        };
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login' => now()]);
    }
}