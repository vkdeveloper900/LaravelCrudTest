<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class post extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
    ];


    public function getImageAttribute()
    {
        // If image not found/ return default  image
        if (empty($this->attributes['image'])) {
            return asset('resource/DefaultImage/No_image_available.svg.png');
        }
//        dd(Storage::disk('post')->url($this->attributes['image']));
        return Storage::disk('post')->url($this->attributes['image']);
    }


}
