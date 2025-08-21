<?php

namespace App\Models;

use App\Enums\Appointments\Waiting\WaitingStatus;
use App\Enums\Appointments\Waiting\WaitingType;
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

    protected $casts =[
        'status'=>WaitingStatus::class,
        'waiting_type'=>WaitingType::class,
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

    public function appointment_bills()
    {
        return $this->morphMany(AppointmentBill::class , 'appointable');
    }


}
