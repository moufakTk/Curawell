<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descount extends Model
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
    public function user_descounts()
    {
        return $this->hasMany(UserDescount::class ,'descount_id');
    }


    /*
     * my FK belongs to
    */


    /*
     * Morph FK
     */

    public function descountable()
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
