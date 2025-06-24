<?php

namespace App\Models;

use App\Enums\Sessions\SessionDoctorStatus;
use Illuminate\Database\Eloquent\Model;

class DoctorSession extends Model
{
    //
    protected $fillable =[
        'work_employee_id',
        'status',
        'from',
        'to',
    ];

    protected $casts =[
        'status'=>SessionDoctorStatus::class,
    ];

    /*
     * who has my PK
    */

    //hasOne
    public function appointments()
    {
        return $this->hasOne(Appointment::class , 'doctor_session_id');
    }

    //hasMane



    /*
     * my FK belongs to
    */

    public function session_doctor()
    {
        return $this->belongsTo(WorkEmployee::class, 'work_employee_id');
    }


}
