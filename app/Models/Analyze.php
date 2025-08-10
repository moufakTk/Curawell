<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analyze extends Model
{
    //

    protected $fillable =[
        'name_en',
        'name_ar',
        'type',
        'price',
        'sample_type',
        'sample_validate',
        'is_active',
    ];

    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function whatAnalyzes()
    {
        return $this->hasMany(WhatAnalyze::class , 'analyze_id');
    }

    public function AnalyzeRelated()
    {
        return $this->hasMany(AnalyzesRelated::class, 'analyze_id');
    }


    /*
     * my FK belongs to
    */
}
