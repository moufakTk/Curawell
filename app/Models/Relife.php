<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relife extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'relife_order_id',
        'phone_number',
        'status',
    ];

    /*
     * who has my PK
    */




    /*
     * my FK belongs to
    */

    public function relife_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function relife_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function relife_order()
    {
        return $this->belongsTo(RelifeOrder::class, 'relife_order_id');
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
