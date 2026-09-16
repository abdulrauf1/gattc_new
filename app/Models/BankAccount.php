<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_title',
        'account_number',
        'bank_name',
        'branch_name',
        'branch_code',
        'iban',
        'purpose',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function courses()
    {
        return $this->hasMany(
            Course::class,
            'bank_account_id'
        );
    }

    public function vouchers()
    {
        return $this->hasMany(
            Voucher::class,
            'bank_account_id'
        );
    }
}