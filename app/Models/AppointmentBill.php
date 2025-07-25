<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentBill extends Model
{
    //

    protected $fillable = [
        'bill_id',
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

    public function appointmentBall_bill()
    {
        return $this->belongsTo(Bill::class , 'bill_id');

    }

    /*
     * Morph FK
     */

    public function appointmentable()
    {
        return $this->morphTo();
    }

}
