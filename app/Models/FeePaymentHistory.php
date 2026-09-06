<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePaymentHistory extends Model
{
    protected $fillable = [
        'voucher_id',
        'fee_payment_id',

        'action',

        'old_status',
        'new_status',

        'amount',

        'performed_by',

        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
    | Payment
    |--------------------------------------------------------------------------
    */

    public function feePayment(): BelongsTo
    {
        return $this->belongsTo(
            FeePayment::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}