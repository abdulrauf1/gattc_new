<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alumni extends Model
{
 use HasFactory;

    protected $table = 'alumnis';

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
        'status' => 'boolean',
        'graduation_year' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}