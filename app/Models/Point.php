<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    //

    protected $fillable = [
        'name_en',
        'name_ar',
        'point_number',
        'is_active',
        'has_source',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function user_points()
    {
        return $this->hasMany(UserPoint::class , 'point_id');
    }


    /*
     * my FK belongs to
    */
}
