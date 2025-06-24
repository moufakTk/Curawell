<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmallService extends Model
{
    //

    protected $fillable = [
        'section_id',
        'name_en',
        'name_ar',
        'price',
        'description_en',
        'description_ar',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function divisions()
    {
        return $this->hasMany(Division::class , 'small_service_id');
    }

    public function whatPhotos()
    {
        return $this->hasMany(WhatPhoto::class , 'small_service_id');
    }


    /*
     * my FK belongs to
    */

    public function smallService_section()
    {
        return $this->belongsTo(Section::class , 'section_id');
    }
}
