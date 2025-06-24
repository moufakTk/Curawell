<?php

namespace App\Models;

use App\Enums\Payments\BallStatus;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'private_num',
        'total_ball',
        'paid_of_ball',
        'status',
    ];

    protected $casts = [
        'status'=>BallStatus::class,
    ];

    /*
     * who has my PK
    */

    //hasOne

    //hasMAny
    public function appointment_bills()
    {
        return $this->hasMany(AppointmentBill::class , 'bill_id');
    }

    public function restores()
    {
        return $this->hasMany(Restore::class , 'bill_id');
    }


    /*
    * my FK belongs to
   */

    public function bill_doctor()
    {
        return $this->belongsTo(Doctor::class , 'doctor_id');
    }

    public function bill_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


}
