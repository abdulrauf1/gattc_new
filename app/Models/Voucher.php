<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_no',
        'fee_configuration_id',
        'admission_id',
        'course_id',
        'course_batch_id',
        'applicant_name',
        'father_name',
        'cnic',
        'phone',
        'amount',

        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'branch_name',

        'issue_date',
        'due_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',

        'issue_date' => 'date',

        'due_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Admission / Application
    |--------------------------------------------------------------------------
    */

    public function admission(): BelongsTo
    {
        return $this->belongsTo(
            Admission::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fee Configuration
    |--------------------------------------------------------------------------
    */

    public function feeConfiguration(): BelongsTo
    {
        return $this->belongsTo(
            FeeConfiguration::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Course
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Course::class
        );
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
    | Payment
    |--------------------------------------------------------------------------
    */

    public function feePayment(): HasOne
    {
        return $this->hasOne(
            FeePayment::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment History
    |--------------------------------------------------------------------------
    */

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(
            FeePaymentHistory::class
        );
    }
}