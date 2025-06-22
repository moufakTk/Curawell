<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //

    protected $fillable = [
        'path_image',
        'imageable',
    ];




    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */


    /*
     * Morph FK
     */

    public function imageable()
    {
        return $this->morphTo();
    }
}
