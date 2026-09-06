<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeConfiguration extends Model
{
    protected $fillable = [
        'fee_type_id',
        'bank_account_id',
        'admission_session_id',
        'course_id',

        'title',
        'amount',

        'effective_from',
        'effective_until',

        'mandatory',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',

        'effective_from' => 'date',

        'effective_until' => 'date',

        'mandatory' => 'boolean',

        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Fee Type
    |--------------------------------------------------------------------------
    */

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(
            FeeType::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bank Account
    |--------------------------------------------------------------------------
    */

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(
            BankAccount::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admission Session
    |--------------------------------------------------------------------------
    */

    public function admissionSession(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class
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
    | Vouchers
    |--------------------------------------------------------------------------
    */

    public function vouchers(): HasMany
    {
        return $this->hasMany(
            Voucher::class
        );
    }
}