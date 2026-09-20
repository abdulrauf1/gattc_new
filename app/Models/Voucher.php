<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_no',
        'admission_session_id',
        'fee_configuration_id',
        'admission_id',
        'course_id',
        'course_batch_id',
        'bank_account_id',
        'voucher_category',

        'applicant_name',
        'father_name',
        'cnic',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',

        'amount',
        'issue_date',
        'due_date',
        'status',
        'remarks',

        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'branch_name',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Admission session associated with the voucher.
     */
    public function admissionSession(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    /**
     * Backward-compatible alias.
     *
     * Some older dashboard code uses $voucher->session.
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    /**
     * Fee configuration used for this voucher.
     */
    public function feeConfiguration(): BelongsTo
    {
        return $this->belongsTo(
            FeeConfiguration::class,
            'fee_configuration_id'
        );
    }

    /**
     * Admission linked to the voucher.
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(
            Admission::class,
            'admission_id'
        );
    }

    /**
     * Course associated with the voucher.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Course::class,
            'course_id'
        );
    }

    /**
     * Course batch associated with the voucher.
     */
    public function courseBatch(): BelongsTo
    {
        return $this->belongsTo(
            CourseBatch::class,
            'course_batch_id'
        );
    }

    /**
     * Bank account assigned to the voucher.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(
            BankAccount::class,
            'bank_account_id'
        );
    }

    /**
     * Payment submitted against this voucher.
     *
     * Assumes fee_payments.voucher_id is the foreign key.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(
            FeePayment::class,
            'voucher_id'
        );
    }
}