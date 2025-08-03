<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTaxi extends Model
{
    //
    protected $fillable =[
        'patient_id',
        'phone',
        'address',
        'date',
        'status',
    ];



    public function orderTaxi_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


}
