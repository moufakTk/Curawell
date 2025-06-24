<?php

namespace App\Models;

use App\Enums\Appointments\EmergencyStatus;
use Illuminate\Database\Eloquent\Model;

class Relief extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'relief_order_id',
        'phone_number',
        'status',
    ];

    protected $casts =[
        'status'=>EmergencyStatus::class,
    ];

    /*
     * who has my PK
    */




    /*
     * my FK belongs to
    */

    public function relief_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function relief_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function relief_order()
    {
        return $this->belongsTo(ReliefOrder::class, 'relief_order_id');
    }


    /*
    * Morph PK
    */

    public function sesstions()
    {
        return $this->morphMany(SessionCenter::class , 'sessionable');
    }

    public function appointment_bills()
    {
        return $this->morphMany(AppointmentBill::class , 'appointmentable');
    }



}
