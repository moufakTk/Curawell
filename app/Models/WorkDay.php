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
        return $this->hasMany(WorkEmployee::class , 'work_day_id');
    }

    public function periods()
    {
        return $this->hasMany(PeriodHomeCare::class , 'work_day_id');
    }
// في موديل User (أو الممرض)


    /*
     * my FK belongs to
    */
}
