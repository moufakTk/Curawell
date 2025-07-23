<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    //
    /*
   * who has my PK
  */
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'chronic_diseases',
        'hereditary_diseases',
        //'hereditary_diseases_ar',
//        'chronic_diseases_ar',
        'new_diseases',
        //'new_diseases_ar',
        //'allergies_ar',
        'allergies',
        'blood_group',
        'weight',
        'height',
    ];
 protected $casts = [
     'chronic_diseases' => 'array',
     'hereditary_diseases' => 'array',
     'new_diseases' => 'array',
     'allergies' => 'array',
 ];

    /*
     * my FK belongs to
    */

    public function medical_history_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


}
