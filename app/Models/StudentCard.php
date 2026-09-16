<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'card_no',
        'photo',
        'issued_at',
        'expiry_date',
        'issued_by',
        'status',
        'remarks',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expiry_date' => 'date',
        'status' => 'boolean',
    ];

    public function admission()
    {
        return $this->belongsTo(
            Admission::class,
            'admission_id'
        );
    }

    public function issuedBy()
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }
}