<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //


    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'discountable',
        'discount_rate',
        'status',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function user_discounts()
    {
        return $this->hasMany(UserDiscount::class ,'discount_id');
    }


    /*
     * my FK belongs to
    */


    /*
     * Morph FK
     */

    public function discountable()
    {
        return $this->morphTo();
    }

    /*
     * Morph PK
     */

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }

}
