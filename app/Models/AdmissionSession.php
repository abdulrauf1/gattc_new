<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionSession extends Model
{
    protected $fillable = [
        'name',
        'session_code',
        'opening_date',
        'closing_date',
        'is_open',
        'description',
    ];

    protected $casts = [
        'opening_date' => 'datetime',
        'closing_date' => 'datetime',
        'is_open' => 'boolean',
    ];

    public function admissions(): HasMany
    {
        return $this->hasMany(
            Admission::class
        );
    }

    public function feeConfigurations(): HasMany
    {
        return $this->hasMany(
            FeeConfiguration::class
        );
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'admission_session_course'
        )->withTimestamps();
    }

    public function isCurrentlyOpen(): bool
    {
        return $this->is_open
            && now()->greaterThanOrEqualTo($this->opening_date)
            && now()->lessThanOrEqualTo($this->closing_date);
    }
}