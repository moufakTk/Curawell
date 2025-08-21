<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountDoctor extends Model
{
    //

    protected $fillable =[
        'doctor_id',
        'discount_id',
    ];




    public function discountDoctor_doctor()
    {
        return $this->belongsTo(Doctor::class , 'doctor_id');
    }

    public function discountDoctor_discount()
    {
        return $this->belongsTo(Discount::class , 'discount_id');
    }


}
