<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admission extends Model
{
    protected $fillable = [
        'admission_no',
        'admission_session_id',
        'course_id',
        'student_name',
        'father_name',
        'cnic',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}