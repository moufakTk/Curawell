<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentBall extends Model
{
    //

    protected $fillable = [
        'ball_id',
        'appointable',
        'total_treatment_amount',
        'paid_of_amount',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function appointmentBall_ball()
    {
        return $this->belongsTo(Ball::class , 'ball_id');

    }

    /*
     * Morph FK
     */

    public function appointmentable()
    {
        return $this->morphTo();
    }

}
