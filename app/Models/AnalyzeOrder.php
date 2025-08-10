<?php

namespace App\Models;

use App\Enums\Orders\AnalyzeOrderStatus;
use Illuminate\Database\Eloquent\Model;

class AnalyzeOrder extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'name',
        'doctor_name',
        'status',
        'price',
        'sample_type',

    ];

    protected $casts =[
        'status'=>AnalyzeOrderStatus::class,
    ];


    /*
     * who has my PK
    */

    public function whatAnalyzes()
    {
        return $this->hasMany(WhatAnalyze::class ,'analyze_order_id');
    }

    public function AnalyzeRelated()
    {
        return $this->hasMany(AnalyzesRelated::class,'analyze_order_id');
    }

    public function samplesRelated()
    {
        return $this->hasMany(SamplsRelated::class,'analyze_order_id');
    }




    /*
     * my FK belongs to
    */
    public function analyzed_order_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function analyzed_ordering_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /*
     * Morph PK
     */

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }





}
