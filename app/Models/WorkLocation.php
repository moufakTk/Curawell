<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLocation extends Model
{
    //

    protected $fillable =[
        'user_id',
        'locationable',


    ];


    /*
     * who has my PK
    */



    /*
     * my FK belongs to
    */

    public function work_location_user()
    {
        return $this->belongsTo(User::class ,'user_id');
    }


    /*
     * Morph   FK
     */

    public function locationable()
    {
        return $this->morphTo();
    }


}
