<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberSequence extends Model
{
    protected $fillable = [
        'name',
        'year',
        'current_number',
    ];

    protected $casts = [
        'year' => 'integer',
        'current_number' => 'integer',
    ];
}