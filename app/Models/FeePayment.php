<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FeePayment extends Model
{
    protected $fillable = [
        'voucher_id',

        'bank_transaction_no',
        'deposit_slip_no',

        'payment_date',
        'amount',

        'payment_method',
        'status',

        'proof_document',

        'verified_by',
        'verified_at',

        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',

        'amount' => 'decimal:2',

        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Voucher
    |--------------------------------------------------------------------------
    */

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(
            Voucher::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Verifier
    |--------------------------------------------------------------------------
    */

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    */

    public function histories(): HasMany
    {
        return $this->hasMany(
            FeePaymentHistory::class
        );
    }


    public function receipt(): HasOne
    {
        return $this->hasOne(FeeReceipt::class);
    }
}