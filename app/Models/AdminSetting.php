<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;

    protected $table = 'tbl_admin_settings';

    protected $fillable = [
        'amount',
        'currency',
        'setting_name',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];
}