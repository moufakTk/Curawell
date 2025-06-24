<?php

namespace App\Models;

use App\Enums\Services\SectionType;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //

    protected $fillable = [
        'name_en',
        'name_ar',
        'brief_description_en',
        'brief_description_ar',
        'section_type',
    ];

    protected $casts =[
        'section_type'=>SectionType::class,
    ];


    /*
     * who has my PK
    */

    public function services()
    {
        return $this->hasMany(Service::class , 'section_id');
    }

    public function small_services()
    {
        return $this->hasMany(SmallService::class , 'section_id');
    }




    /*
     * my FK belongs to
    */


    /*
     * Morph  Pk
     */
    public function work_locations()
    {
        return $this->morphMany(WorkLocation::class , 'locationable');
    }

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
}
