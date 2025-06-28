<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Gender;
use App\Enums\Users\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'first_name_ar',
        'last_name',
        'last_name_ar',
        'email',
        'password',
        'phone',
        'address',
        'address_ar',
        'gender',
        'user_type',
        'is_active',
        'age',
        'birthday',
        'reset_password_token',
        'reset_password_token_expires_at',
        'email_verified_at',
        'phone_verified_at',
    ];

    use HasApiTokens,HasFactory, Notifiable ,HasRoles;

    protected $casts = [
        'gender'=>Gender::class,
        'user_type'=>UserType::class,
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
    public function codes(){
        return $this->hasMany(verificationCode::class,'user_id');
    }

    //hasOne
    public function patient()
    {
        return $this->hasOne(patient::class,'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class ,'doctor_id');
    }

    public function work_location()
    {
        return $this->hasOne(WorkLocation::class ,'work_location_id');

    }

    public function team_one()
    {
        return $this->hasOne(Ambulance_team::class ,'user_doctor_id'); //As Doctor
    }
    public function team_two()
    {
        return $this->hasOne(Ambulance_team::class ,'user_nurse_id'); //As Nurse
    }

    public function team_three()
    {
        return $this->hasOne(Ambulance_team::class ,'user_driver_id'); //As Driver
    }



    //hasMany
    public function work_employees()
    {
        return $this->hasMany(WorkEmployee::class ,'user_id');
    }





    /*
     * my FK belongs to
    */



    /*
     * Morph PK
     */

    public function assigned()
    {
        return $this->morphMany(Assigned::class ,'assignable');
    }


}







