<?php

namespace App\Models;

use App\Enums\Users\DoctorType;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //

    protected $fillable =[
        'user_id',
        'respective-en',
        'respective-ar',
        'experience_years',
        'services-en',
        'services-ar',
        'bloodGroup',
        'start_in',
        'hold_end',
        'evaluation',
        'doctor_type',
    ];

    protected $casts =[
        'doctor_type' =>DoctorType::class,
    ];


    /*
     * who has my PK
    */

    //hasOne
    public function doctor_examination()
    {
       return $this->hasOne(Doctor_examin::class,'doctor_id');
    }

    //hasMany
    public function evaluations()
    {
        return $this->hasMany(Evaluction::class,'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'doctor_id');
    }

    public function waiting()
    {
        return $this->hasMany(Waiting::class,'doctor_id');
    }

    public function relife()
    {
        return $this->hasMany(Relife::class,'doctor_id');
    }

    public function ball()
    {
        return $this->hasMany(Ball::class,'doctor_id');
    }

    public function analyze_orders()
    {
        return $this->hasMany(AnalyzeOrder::class,'doctor_id');
    }

    public function skiagraph_Orders()
    {
        return $this->hasMany(SkiagraphOrder::class,'doctor_id');
    }

    public function divisions()
    {
        return $this->hasMany(Division::class,'doctor_id');
    }

    /*
     * my FK belongs to
    */

    public function doctor_user()
    {
        return $this->belongsTo(User::class ,'user_id');
    }

    /*
     * Morph  PK
     */

    public function comments()      //(Morph nullable)
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }

}
