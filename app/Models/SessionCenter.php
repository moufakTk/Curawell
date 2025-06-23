<?php

namespace App\Models;

use App\Enums\Sessions\SessionCenterType;
use Illuminate\Database\Eloquent\Model;

class SessionCenter extends Model
{
    //
    protected $fillable = [
    'sessionable',
    'diagnosis',
    'symptoms',
    'medicines',
    'doctor_examination',
    'discount',
    'session_type',
    ];


    protected $casts = [
        'session_type'=>SessionCenterType::class,
    ];
    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function treatments()
    {
        return $this->hasMany(Treatment::class , 'session_id');
    }




    /*
     * my FK belongs to
    */



    /*
     * Morph FK
     */

    public function sessionable ()
    {
        return $this->morphTo();
    }



}
