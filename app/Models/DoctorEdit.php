<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorEdit extends Model
{
    //

    protected $fillable =[
        'doctor_id',
        'bill_id',
        'edit',
        'price',
        'status',
    ];

    public function doctorEdit_doctor()
    {
        return $this->belongsTo(Doctor::class , 'doctor_id');
    }

    public function doctorEdit_bill()
    {
        return $this->belongsTo(Bill::class , 'bill_id');
    }

}
