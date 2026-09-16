<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

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

    public function voucher()
    {
        return $this->belongsTo(
            Voucher::class,
            'voucher_id'
        );
    }

    public function histories()
    {
        return $this->hasMany(
            FeePaymentHistory::class,
            'fee_payment_id'
        );
    }

    public function receipt()
    {
        return $this->hasOne(
            FeeReceipt::class,
            'fee_payment_id'
        );
    }
}