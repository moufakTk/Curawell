<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    use HasFactory;

    protected $fillable =[
        'user_id',
        'civil_id_number',
        'alternative_phone',
        'totalPoints',
    ];


    /*
    * who has my PK
   */

    //hasOne
    public function medical_history()
    {
        return $this->hasOne(MedicalHistory::class ,'patient_id');
    }

    //hasMane
    public function evaluation()
    {
        return $this->hasMany(Evaluction::class ,'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class ,'patient_id');
    }

    public function waitings()
    {
        return $this->hasMany(Waiting::class ,'patient_id');
    }

    public function relifes()
    {
        return $this->hasMany(Relief::class ,'patient_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class ,'patient_id');
    }

    public function analyze_orders()
    {
        return $this->hasMany(AnalyzeOrder::class ,'patient_id');
    }
    public function skiagraph_Orders()
    {
        return $this->hasMany(SkiagraphOrder::class ,'patient_id');
    }

    public function relife_orders()
    {
        return $this->hasMany(ReliefOrder::class ,'patient_id');
    }

    public function samples()
    {
        return $this->hasMany(Sample::class ,'patient_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class ,'patient_id');
    }

    public function user_points()
    {
        return $this->hasMany(UserPoint::class ,'patient_id');
    }

    public function users_replacements()
    {
        return $this->hasMany(UserReplacement::class ,'patient_id');
    }

    public function user_descounts()
    {
        return $this->hasMany(UserDiscount::class ,'patient_id');
    }

    public function appointment_homes()
    {
        return $this->hasMany(AppointmentHomeCare::class ,'patient_id');
    }


    public function assigneds()
    {
        return $this->hasMany(Assigned::class ,'patient_id');
    }

    public function orderTaxis()
    {
        return $this->hasMany(OrderTaxi::class ,'patient_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class ,'patient_id');
    }


    /*
     * my FK belongs to
    */

    public function patient_user()
    {
        return $this->belongsTo(User::class ,'user_id');
    }

    /*
     * Morph  PK
     */

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }

}
