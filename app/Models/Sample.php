<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'process_take',
        'time_take',
        'time_don',
        'sample_type',
        'status',
    ];

    /*
   * who has my PK
  */



    /*
    * my FK belongs to
   */

    public function sample_patient()
    {
        return $this->belongsTo(patient::class , 'patient_id');
    }
}
