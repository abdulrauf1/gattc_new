<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeDepositDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'fee_category',
        'fee_code',
        'fee_name',
        'amount',
        'mandatory',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'mandatory' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Course
    |--------------------------------------------------------------------------
    */

    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Course::class,
            'course_id'
        );
    }
}