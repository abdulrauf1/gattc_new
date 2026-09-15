<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionSession extends Model
{
    protected $fillable = [
        'title',
        'opening_date',
        'closing_date',
        'is_open',
        'description',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'closing_date' => 'date',
        'is_open' => 'boolean',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'admission_session_course'
        )->withTimestamps();
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}