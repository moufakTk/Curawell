<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluction extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'stars_number',
    ];

    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function evaluation_doctor()
    {
        return $this->belongsTo(Doctor::class , 'doctor_id');
    }
    public function evaluation_patient(){
        return $this->belongsTo(Patient::class , 'patient_id');
    }
}
