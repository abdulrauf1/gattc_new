<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'bank_account_id',
        'title',
        'slug',
        'course_type',
        'description',
        'duration',
        'eligibility',
        'fee_amount',
        'image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(
            CourseCategory::class,
            'course_category_id'
        );
    }

    public function bankAccount()
    {
        return $this->belongsTo(
            BankAccount::class,
            'bank_account_id'
        );
    }

    public function sessions()
    {
        return $this->belongsToMany(
            AdmissionSession::class,
            'admission_session_course',
            'course_id',
            'admission_session_id'
        )->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(
            Admission::class,
            'course_id'
        );
    }

    public function vouchers()
    {
        return $this->hasMany(
            Voucher::class,
            'course_id'
        );
    }
}