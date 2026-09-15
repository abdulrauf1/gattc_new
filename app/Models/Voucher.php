<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_no',
        'admission_session_id',
        'course_id',
        'admission_id',
        'bank_account_id',
        'voucher_type',
        'applicant_name',
        'father_name',
        'cnic',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'amount',
        'issue_date',
        'due_date',
        'status',
        'remarks',

        // Bank snapshot
        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'branch_name',
        'branch_code',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function payment()
    {
        return $this->hasOne(FeePayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getVoucherTypeLabelAttribute(): string
    {
        return match ($this->voucher_type) {
            'admission' => 'Admission',
            'hostel' => 'Hostel',
            'readmission' => 'Readmission',
            default => ucfirst($this->voucher_type),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }
}