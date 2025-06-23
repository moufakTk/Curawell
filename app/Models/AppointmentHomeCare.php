<?php

namespace App\Models;

use App\Enums\Appointments\AppointmentHomeCareType;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;

class AppointmentHomeCare extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'nurse_session_id',
        'type',
        'gender',
        'location_en',
        'location_ar',
        'phone_number',
        'price',
        'explain',
    ];

    protected $casts =[
        'type'=>AppointmentHomeCareType::class,
        'gender'=>Gender::class,
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function appointment_home_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }

    public function appointment_home_session_nurse(){
        return $this->belongsTo(NurseSession::class , 'nurse_session_id');
    }
}
