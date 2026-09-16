<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

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

    public function session()
    {
        return $this->belongsTo(
            AdmissionSession::class,
            'admission_session_id'
        );
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function vouchers()
    {
        return $this->hasMany(
            Voucher::class,
            'admission_id'
        );
    }

    public function payments()
    {
        return $this->hasManyThrough(
            FeePayment::class,
            Voucher::class,
            'admission_id',
            'voucher_id',
            'id',
            'id'
        );
    }

    public function studentCards()
    {
        return $this->hasMany(
            StudentCard::class,
            'admission_id'
        );
    }
}