<?php

namespace App\Models;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Appointments\appointment\AppointmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //

    use HasFactory;

    protected $fillable =[
        'patient_id',
        'doctor_id',
        'doctor_session_id',
        'phone_number',
        'status',
        'delivery',
        'delivery_location_en',
        //'delivery_location_ar',
        'appointment_type',
    ];


    protected $casts =[
        'status'=>AppointmentStatus::class ,
        'appointment_type'=>AppointmentType::class ,
    ];

    /*
     * who has my PK
    */

    public function user_rep()
    {
        return $this->hasOne(UserReplacement::class , 'appointment_id');
    }

    /*
     * my FK belongs to
    */

    public function appointment_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');

    }

    public function appointment_doctor()
    {
        return $this->belongsTo(Doctor::class , 'doctor_id');
    }

    public function appointment_doctor_session()
    {
        return $this->belongsTo(DoctorSession::class , 'doctor_session_id');
    }

    /*
     * Morph   PK
     */

    public function sesstions()
    {
        return $this->morphMany(SessionCenter::class , 'sessionable');
    }

    public function appointment_bills()
    {
        return $this->morphMany(AppointmentBill::class , 'appointable');
    }

    public function user_points ()            // (nullable  Morph)
    {
        return $this->morphMany(UserPoint::class , 'pointable');
    }




}
