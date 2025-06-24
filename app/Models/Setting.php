<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //

    protected $fillable = [
        'inquiry_number',
        'phone',
        'email',
        'site_name_en',
        'site_name_ar',
        'address_en',
        'address_ar',
        'logo',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */
}
