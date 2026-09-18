<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


#[Fillable(['name', 'email', 'password' , 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }


    /*
|--------------------------------------------------------------------------
| Financial / Admission Relationships
|--------------------------------------------------------------------------
*/

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(
            FeePayment::class,
            'verified_by'
        );
    }

    public function verifiedAdmissionFees(): HasMany
    {
        return $this->hasMany(
            Admission::class,
            'fee_verified_by'
        );
    }

    public function admittedStudents(): HasMany
    {
        return $this->hasMany(
            Admission::class,
            'admitted_by'
        );
    }

    public function feePaymentHistories(): HasMany
    {
        return $this->hasMany(
            FeePaymentHistory::class,
            'performed_by'
        );
    }
}
