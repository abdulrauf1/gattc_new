<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;


class Course extends Model
{
    protected $fillable = [
        'course_category_id',
        'title',
        'slug',
        'code',
        'short_description',
        'description',
        'duration',
        'qualification',
        'fee',
        'image',
        'featured',
        'status',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'featured' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            CourseCategory::class,
            'course_category_id'
        );
    }

    public function batches(): HasMany
    {
        return $this->hasMany(
            CourseBatch::class
        );
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(
            Admission::class
        );
    }

    public function admissionSessions(): BelongsToMany
    {
        return $this->belongsToMany(
            AdmissionSession::class,
            'admission_session_course'
        )->withTimestamps();
    }

    public function feeConfigurations(): HasMany
    {
        return $this->hasMany(
            FeeConfiguration::class
        );
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(
            Voucher::class
        );
    }

    public function alumni(): HasMany
    {
        return $this->hasMany(
            Alumni::class
        );
    }
}