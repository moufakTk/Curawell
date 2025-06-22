<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'password',
        'phone',
        'location_en',
        'location_ar',
        'gender',
        'user_type',
        'is_active',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    }


    /*
     * who has my PK
    */

    //hasOne
    protected function patient()
    {
        return $this->hasOne(Patient::class ,'patient_id');
    }

    protected function doctor()
    {
        return $this->hasOne(Doctor::class ,'doctor_id');
    }

    protected function work_location()
    {
        return $this->hasOne(WorkLocation::class ,'work_location_id');

    }

    protected function team_one()
    {
        return $this->hasOne(Ambulance_team::class ,'user_doctor_id'); //As Doctor
    }
    protected function team_two()
    {
        return $this->hasOne(Ambulance_team::class ,'user_nurse_id'); //As Nurse
    }

    protected function team_three()
    {
        return $this->hasOne(Ambulance_team::class ,'user_driver_id'); //As Driver
    }



    //hasMany
    protected function work_employees()
    {
        return $this->hasMany(WorkImployee::class ,'user_id');
    }





    /*
     * my FK belongs to
    */


}







