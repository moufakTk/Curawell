<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replacement extends Model
{
    //

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'replece_point_num',
        'is_active',
    ];


    /*
     * who has my PK
    */

    //hasOne

    //hasMany
    public function replacements()
    {
        return $this->hasMany(UserReplacement::class , 'replacement_id');
    }


    /*
     * my FK belongs to
    */
}
