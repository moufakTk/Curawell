<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'inquiry_number',
        'complaint_number',
        'phone',
        'email',
        'site_name',
        'preface_en',
        'preface_ar',
        'wise_en',
        'wise_ar',
        //'site_name_ar',
        'address_en',
        //'address_ar',
        'logo',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */
}
