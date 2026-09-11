<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeReceipt extends Model
{
    protected $fillable = [
        'fee_payment_id',
        'receipt_no',
        'issued_at',
        'issued_by',
        'remarks',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function feePayment(): BelongsTo
    {
        return $this->belongsTo(FeePayment::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}