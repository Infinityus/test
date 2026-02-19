<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Certificate extends Model
{
    use HasFactory;

    protected $table = 'tbl_certificate';

    protected $fillable = [
        'user_id',
        'course_name',
        'certificate',
        'status',
    ];

    protected $casts = [
        'certificate' => 'boolean',
        'status'      => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}