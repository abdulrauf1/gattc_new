<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'course',
        'graduation_year',
        'organization',
        'designation',
        'bio',
        'photo',
        'status',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'status' => 'boolean',
    ];
}