<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDiscount extends Model
{
    //

    protected $fillable =[
        'patient_id',
        'discount_id',
        'discount_rate',
    ];

    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */
    public function user_desc_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user_desc_descount(){
        return $this->belongsTo(Discount::class, 'discount_id');
    }

}
