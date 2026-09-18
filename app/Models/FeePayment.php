<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeePayment extends Model
{
    protected $fillable = [
        'voucher_id',
        'deposit_slip_no',
        'bank_transaction_no',
        'payment_date',
        'amount',
        'payment_method',
        'payment_slip',
        'status',
        'verified_at',
        'remarks',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(
            Voucher::class
        );
    }

    public function histories(): HasMany
    {
        return $this->hasMany(
            FeePaymentHistory::class
        );
    }
}