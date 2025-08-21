<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountDivision extends Model
{
    //

    protected $fillable=[
        'discount_id',
        'division_id',
        'discount_amount',
    ];




    public function discountDivision_division()
    {
        return $this->belongsTo(Division::class,'division_id');
    }

    public function discountDivision_discount()
    {
     return $this->belongsTo(Discount::class,'discount_id');
    }

}
