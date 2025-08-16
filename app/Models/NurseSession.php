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
    // منجيب معلومات الممرض من جدول اليوزر
    public function nurse(){
        return $this->hasOneThrough(
            User::class,//الجدول النهائي يلي بدي جيب منو المعلومات
            WorkEmployee::class,   // الجدول الوسيط يلي عن طريقو بدي وصل للجدول النهائي
            'id',//ال PK المفتاح الاساسي بجدول الوسيط (WorkEmployee.id)
            'id',              //ال PK المفتاح الاساسي بالجدول النهائي (user.id)
        'work_employee_id',//ال FK الموجود بالجدول يلي انا واقف عندو(NurseSession.work_employee_id)
            // يلي مربوط بالجدول الوسيط (WorkEmployee)
        'user_id');//ال FK الموجود بالجدول الوسيط(WorkEmployee.user_id) يلي مربوط بالجدول النهائي(user)
    }

    // منجيب اليوم المربوط بالسيشن يلي فيه التاريخ النظامي
 public function session_day(){
        return $this->hasOneThrough(
            workDay::class
            ,WorkEmployee::class,
        'id',
        'id',
        'work_employee_id',
        'work_day_id',
        );
 }



}
