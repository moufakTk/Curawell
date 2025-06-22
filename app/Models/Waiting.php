<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiting extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'phone_number',
        'status',
        'waiting_type',
    ];

    /*
     * who has my PK
    */



    /*
     * my FK belongs to
    */
    public function waiting_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function waiting_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }



    /*
    * Morph PK
    */

    public function sesstions()
    {
        return $this->morphMany(SessionCenter::class , 'sessionable');
    }

    public function appointment_balls()
    {
        return $this->morphMany(AppointmentBall::class , 'appointmentable');
    }


}
