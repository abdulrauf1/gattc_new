<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseBatch extends Model
{
    protected $fillable = [
        'course_id',
        'batch_name',
        'start_date',
        'end_date',
        'capacity',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Course::class
        );
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(
            Admission::class,
            'course_batch_id'
        );
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(
            Voucher::class,
            'course_batch_id'
        );
    }
}