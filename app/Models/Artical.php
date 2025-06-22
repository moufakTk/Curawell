<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artical extends Model
{
    //


    protected $fillable = [
        'title_en',
        'title_ar',
        'brief_description_en',
        'brief_description_ar',
        'path_link',
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

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }


}
