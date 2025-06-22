<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    //

    protected $fillable = [
        'day_en',
        'day_ar',
        'status',
        'history',
    ];


    /*
     * who has my PK
    */


    //hasOne

    //hasMany
    public function work_employees()
    {
        return $this->hasMany(WorkImployee::class , 'work_day_id');
    }


    /*
     * my FK belongs to
    */
}
