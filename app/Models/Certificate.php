<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'tbl_certificate';

    protected $fillable = [
        'user_id',
        'user_name',
        'course_name',
        'certificate',
        'mobile',
        'certificate_id',
        'issue_date',
        'expiry_date',
        'issued_by',
        'description',
        'metadata'
    ];

    protected $casts = [
        'certificate' => 'boolean',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Generate unique certificate ID
     */
    public static function generateCertificateId(): string
    {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Y');
    }

    /**
     * Scope to get certificates by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get certificates by mobile
     */
    public function scopeByMobile($query, $mobile)
    {
        return $query->where('mobile', $mobile);
    }

    /**
     * Scope to get only completed certificates
     */
    public function scopeCompleted($query)
    {
        return $query->where('certificate', true);
    }

    /**
     * Scope to get certificates by course
     */
    public function scopeByCourse($query, $courseName)
    {
        return $query->where('course_name', 'like', '%' . $courseName . '%');
    }

    /**
     * Get certificate status with badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->certificate 
            ? '<span class="badge bg-success">Completed</span>'
            : '<span class="badge bg-warning">In Progress</span>';
    }

    /**
     * Get formatted issue date
     */
    public function getFormattedIssueDateAttribute(): string
    {
        return $this->issue_date 
            ? $this->issue_date->format('d M Y') 
            : 'Not issued';
    }
}