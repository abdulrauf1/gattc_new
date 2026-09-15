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
        'course_id',
        'admission_id',
        'bank_account_id',
        'voucher_type',

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
        'branch_code',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(FeePayment::class);
    }
}