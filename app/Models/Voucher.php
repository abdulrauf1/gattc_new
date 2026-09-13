<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_no',
        'fee_configuration_id',
        'admission_session_id',
        'admission_id',
        'course_id',
        'course_batch_id',

        'bank_account_id',
        'voucher_category',

        'applicant_name',
        'father_name',
        'cnic',
        'phone',

        'amount',
        'fee_details',

        'issue_date',
        'due_date',
        'status',
        'remarks',

        // Historical bank snapshot
        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'branch_name',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'fee_details' => 'array',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function feeConfiguration(): BelongsTo
    {
        return $this->belongsTo(FeeConfiguration::class);
    }

    public function admissionSession(): BelongsTo
    {
        return $this->belongsTo(AdmissionSession::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    public function courseBatch(): BelongsTo
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