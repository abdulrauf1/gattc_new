<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    protected $fillable = [
        'account_title',
        'account_number',
        'bank_name',
        'branch_name',
        'branch_code',
        'iban',
        'account_type',
        'purpose',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function feeConfigurations(): HasMany
    {
        return $this->hasMany(
            FeeConfiguration::class
        );
    }
}