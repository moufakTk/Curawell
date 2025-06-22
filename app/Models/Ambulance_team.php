<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambulance_team extends Model
{
    //

    protected $fillable =[
        'user_doctor_id',
        'user_nurse_id',
        'user_driver_id',
        'status',
        'working',

    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function  details_Transports()
    {
        return $this->hasMany(DetailsTransport::class);
    }

    /*
     * my FK belongs to
    */

    protected function ambulance_one_user()
    {
        return $this->belongsTo(User::class, 'user_doctor_id');
    }

    protected function ambulance_two_user()
    {
        return $this->belongsTo(User::class, 'user_nurse_id');
    }

    protected function ambulance_three_user()
    {
        return $this->belongsTo(User::class, 'user_driver_id');
    }


}
