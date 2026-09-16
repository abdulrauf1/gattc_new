<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_payment_id',
        'user_id',
        'old_status',
        'new_status',
        'remarks',
    ];

    /*
    |--------------------------------------------------------------------------
    | Payment
    |--------------------------------------------------------------------------
    */

    public function payment()
    {
        return $this->belongsTo(
            FeePayment::class,
            'fee_payment_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User / Admin who performed the action
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}