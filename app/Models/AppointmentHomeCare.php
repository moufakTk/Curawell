<?php

namespace App\Models;

use App\Enums\Appointments\AppointmentHomeCareType;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class AppointmentHomeCare extends Model
{
    use HasFactory;
    //

    protected $fillable = [
        'patient_id',
        'nurse_session_id',
        'type',
        'gender',
        'location',
        //'location_ar',
        'phone_number',
        'notes',
        'price',
        'explain',
        'status'
    ];
protected $hidden=['gender','created_at','updated_at'];
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


    /*
     * Morph   PK
     */

    public function user_points ()            // (nullable  Morph)
    {
        return $this->morphMany(UserPoint::class , 'pointable');
    }


// scope


    public function scopeAppointmentsOwnedByNurse(Builder $query, User $nurse): Builder
    {
        return $query->whereHas('appointment_home_session_nurse.nurse', function ($q) use ($nurse) {
            $q->where('users.id', $nurse->id);
        });
    }
    public function patient_user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class ,
            Patient::class,
            'id',
            'id',
            'patient_id',
            'user_id'
        );
    }


}
