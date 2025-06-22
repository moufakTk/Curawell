<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medical_history extends Model
{
    //
    protected $fillable = [
        'patient_id',
        'chronic_diseases-en',
        'chronic_diseases-ar',
        'hereditary_diseases-en',
        'hereditary_diseases-ar',
        'new_diseases_en',
        'new_diseases_ar',
        'sensitivities_en',
        'sensitivities_ar',
        'bloodGroup',
        'weight',
        'height',
    ];

    /*
   * who has my PK
  */


    /*
     * my FK belongs to
    */

    public function medical_history_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


}
