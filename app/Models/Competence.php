<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    //

    protected $fillable = [
        'service_id',
        'name_en',
        'name_ar',
        'brief_description_en',
        'brief_description_ar',

    ];

    protected $casts=[
        'brief_description_en'=>'array',
        'brief_description_ar'=>'array',
    ];


    /*
     * who has my PK
    */



    /*
     * my FK belongs to
    */

    public function competence_services()
    {
        return $this->belongsTo(Service::class , 'service_id');
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
