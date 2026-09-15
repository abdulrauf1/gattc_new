<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'course_category_id',
        'bank_account_id',
        'title',
        'slug',
        'course_type',
        'description',
        'duration',
        'eligibility',
        'fee_amount',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            CourseCategory::class,
            'course_category_id'
        );
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function admissionSessions(): BelongsToMany
    {
        return $this->belongsToMany(
            AdmissionSession::class,
            'admission_session_course'
        )->withTimestamps();
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}