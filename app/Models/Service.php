<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //

    protected $fillable = [
        'section_id',
        'name_en',
        'name_ar',
        //'brief_description_en',
        //'brief_description_ar',
        'details_services_en',
        'details_services_ar',
    ];

    protected $casts =[
        'details_services_en'=>'array',
        'details_services_ar'=>'array',

    ];


    /*
     * who has my PK
    */

    public function competences()
    {
        return $this->hasMany(Competence::class , 'service_id');
    }

    /*
     * my FK belongs to
    */

    public function service_section()
    {
        return $this->belongsTo(Section::class , 'section_id');
    }

    /*
     * Morph PK
     */

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }

}
