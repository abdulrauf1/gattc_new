<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
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

    /**
     * Courses available in this admission session.
     */
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'admission_session_course',
            'admission_session_id',
            'course_id'
        )->withTimestamps();
    }

    /**
     * Admissions belonging to this session.
     */
    public function admissions()
    {
        return $this->hasMany(
            Admission::class,
            'admission_session_id'
        );
    }

    

    /**
     * Vouchers belonging to this session.
     */
    public function vouchers()
    {
        return $this->hasMany(
            Voucher::class,
            'admission_session_id'
        );
    }
}