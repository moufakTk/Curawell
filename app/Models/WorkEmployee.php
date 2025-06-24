<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkEmployee extends Model
{
    //

    protected $fillable =[
        'work_day_id',
        'user_id',
        'status',
        'from',
        'to',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function doctor_sessions()
    {
        return $this->hasMany(DoctorSession::class , 'work_employee_id');
    }

    public function nurse_sessions()
    {
        return $this->hasMany(NurseSession::class , 'work_employee_id');
    }


    /*
     * my FK belongs to
    */

    public function work_employee_user()
    {

        return $this->belongsTo(User::class ,'user_id');
    }

    public function work_employee_Day()
    {
        return $this->belongsTo(WorkDay::class ,'work_day_id');
    }





}
