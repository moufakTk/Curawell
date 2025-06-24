<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatPhoto extends Model
{
    //
    protected $fillable =[
        'skiagraph_order_id',
        'small_service_id',
        'price',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function whatPhoto_skaigraph()
    {
        return $this->belongsTo(SkiagraphOrder::class, 'skiagraph_order_id');
    }

    public function whatPhoto_small_servise()
    {
        return $this->belongsTo(SmallService::class, 'small_service_id');
    }

}
