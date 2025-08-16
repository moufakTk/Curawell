<?php

namespace App\Models;

use App\Enums\SampleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;
    //

    protected $fillable = [
        'patient_id',
        'process_take',
        'time_take',
        'time_don',
        'sample_type',
        'status',
    ];

    protected $casts = [
        'sample_type'=>SampleType::class,
    ];

    /*
   * who has my PK
  */

    public function samplesRelated()
    {
        return $this->hasMany(SamplsRelated::class ,'sample_id');
    }

    /*
    * my FK belongs to
   */

    public function sample_patient()
    {
        return $this->belongsTo(patient::class , 'patient_id');
    }
}
