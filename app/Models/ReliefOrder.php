<?php

namespace App\Models;

use App\Enums\Orders\EmergencyOrder\EmergencyOrderDestination;
use App\Enums\Orders\EmergencyOrder\EmergencyOrderStatus;
use App\Enums\Orders\EmergencyOrder\EmergencyOrderType;
use Illuminate\Database\Eloquent\Model;

class ReliefOrder extends Model
{
    //

    protected $fillable =[
        'patient_id',
        'order_type',
        'destination',
        'use_car',
        'status',
    ];

    protected $casts = [
        'order_type'=>EmergencyOrderType::class,
        'destination'=>EmergencyOrderDestination::class,
        'status'=>EmergencyOrderStatus::class,
    ];

    /*
     * who has my PK
    */

    //hasOne
    public function relifes()
    {
        return $this->hasOne(Relief::class ,'relife_order_id');
    }

    public function details_Transport()
    {
        return $this->hasOne(DetailsTransport::class ,'relife_order_id');
    }


    //hasMany


    /*
     * my FK belongs to
    */

    public function relife_order_patient()
    {
        $this->belongsTo(Patient::class ,'patient_id');
    }
}
