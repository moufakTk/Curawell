<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatAnalyze extends Model
{
    //
    protected $fillable =[
        'analyze_order_id',
        'analyze_id',
        'price',

    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function WhatAnalyze_order()
    {
        return $this->belongsTo(AnalyzeOrder::class , 'analyze_order_id');
    }

    public function whatAnalyze_analyze()
    {
        return $this->belongsTo(Analyze::class, 'analyze_id');
    }
}
