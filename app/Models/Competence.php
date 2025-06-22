<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    //

    protected $fillable = [
        'servise_id',
        'name_en',
        'name_ar',
        'brief_description_en',
        'brief_description_ar',

    ];


    /*
     * who has my PK
    */



    /*
     * my FK belongs to
    */

    public function competence_services()
    {
        return $this->belongsTo(Servise::class , 'servise_id');
    }



    /*
     * Morph  PK
     */
    public function work_locations()
    {
        return $this->morphMany(WorkLocation::class, 'locationable');
    }


    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }


}
