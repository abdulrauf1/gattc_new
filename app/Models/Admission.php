<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Admission extends Model
{
    protected $fillable = [
        'application_no',
        'admission_no',
        'admission_session_id',

        'course_id',
        'course_batch_id',

        'full_name',
        'father_name',
        'date_of_birth',
        'gender',

        'cnic',
        'phone',
        'email',

        'address',
        'district',
        'tehsil',

        'qualification',
        'institute',
        'passing_year',

        'photo',
        'cnic_front',
        'cnic_back',
        'qualification_document',

        
        'admission_status',

        'remarks',

        'submitted_at',

        'fee_verified_at',
        'fee_verified_by',

        'admitted_at',
        'admitted_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',

        'submitted_at' => 'datetime',

        'fee_verified_at' => 'datetime',

        'admitted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Admission Session
    |--------------------------------------------------------------------------
    */

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Course
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Course Batch
    |--------------------------------------------------------------------------
    */

    public function batch(): BelongsTo
    {
        return $this->belongsTo(
            CourseBatch::class,
            'course_batch_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Voucher
    |--------------------------------------------------------------------------
    */

    public function voucher(): HasOne
    {
        return $this->hasOne(Voucher::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Fee Payment
    |--------------------------------------------------------------------------
    |
    | Admission -> Voucher -> FeePayment
    |
    */

    public function feePayment(): HasOneThrough
    {
        return $this->hasOneThrough(
            FeePayment::class,
            Voucher::class,
            'admission_id',
            'voucher_id',
            'id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fee Verified By
    |--------------------------------------------------------------------------
    */

    public function feeVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'fee_verified_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admitted By
    |--------------------------------------------------------------------------
    */

    public function admittedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'admitted_by'
        );
    }
}