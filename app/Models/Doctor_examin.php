<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_examin extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'price',
        'is_discounted',
        'discount_rate',

    ];


    /*
     * who has my PK
    */



    /*
     * my FK belongs to
    */

    public function examination_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }



    /*
     * Morph PK
     */

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

}
