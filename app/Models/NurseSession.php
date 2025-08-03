<?php

namespace App\Models;

use App\Enums\Sessions\SessionNurseStatus;
use Illuminate\Database\Eloquent\Model;

class NurseSession extends Model
{
    //

    protected $fillable = [
        'work_employee_id',
        'status',
        'time_in',

    ];

    protected $casts = [
        'status'=>SessionNurseStatus::class,
        'time_in'=>'datetime:H:i:s',
    ];


    /*
     * who has my PK
    */

    //hasOne
    public function appointments_home()
    {
        return $this->hasOne(AppointmentHomeCare::class ,'nurse_session_id');
    }

    //hasMany


    /*
     * my FK belongs to
    */

    public function nurse_session()
    {
        return $this->belongsTo(WorkEmployee::class ,'work_employee_id');
    }
}
