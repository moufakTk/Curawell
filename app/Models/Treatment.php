<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    //

    protected $fillable = [
        'session_center_id',
        'division_id',
        'small_service_price',
        'small_service_num',
        'discount_price',
        'total',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function treatment_division()
    {
        return $this->belongsTo(Division::class , 'division_id');
    }

    public function treatment_session()
    {
       return $this->belongsTo(SessionCenter::class , 'session_center_id');
    }
}
