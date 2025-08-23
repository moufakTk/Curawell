<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    //
    use HasFactory;



    protected $fillable = [
        'title_en',
        'title_ar',
        'brief_description_en',
        'brief_description_ar',
        'path_link',
        'is_active',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */


    /*
     * Morph PK
     */

    public function image()
    {
        // اسم العلاقة اختاره "image" لأن بدنا صورة واحدة فقط
        return $this->morphOne(\App\Models\Image::class, 'imageable');
    }

    public function getImageUrlAttribute(): ?string
    {

        return $this->image->url?? null;
    }
}
