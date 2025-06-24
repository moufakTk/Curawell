<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    //

    protected $fillable = [
        'small_service_id',
        'doctor_id',
        'is_discounted',
        'discount_rate',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function treatments()
    {
        return $this->hasMany(Treatment::class , 'division_id');
    }


    /*
     * my FK belongs to
    */

    public function division_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function division_small_service()
    {
        return $this->belongsTo(SmallService::class, 'small_service_id');
    }



    /*
     * Morph PK
     */

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
