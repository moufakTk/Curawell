<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    use HasFactory;


    protected $fillable = [
        'service_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'discountable',
        'start_date',
        'end_date',
        'discount_rate',
        'active',
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

    public function discount_service()
    {
        return $this->belongsTo(Service::class ,'service_id');
    }


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
