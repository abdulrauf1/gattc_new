<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function images()
    {
        return $this->hasMany(
            GalleryImage::class,
            'gallery_id'
        )->orderBy('sort_order');
    }
}