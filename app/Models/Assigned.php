<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assigned extends Model
{
    //

    protected $fillable =[
        'patient_id',
        'assignedable_id',
        'assignedable_type',
        'active',

    ];



    /*
    * who has my PK
   */



    /*
     * my FK belongs to
    */


    public function assigned_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


    /*
     * Morph FK
     */

    public function assignedable()
    {
        return $this->morphTo();
    }

}
