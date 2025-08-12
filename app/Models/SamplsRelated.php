<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SamplsRelated extends Model
{
    //

    protected $fillable =[
        'analyze_order_id',
        'sample_id',
    ];



    public function SamplesRelated_sample()
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }

    public function SamplesRelated_AnalyzeOrder()
    {
        return $this->belongsTo(AnalyzeOrder::class, 'analyze_order_id');
    }



}
