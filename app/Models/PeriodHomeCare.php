<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodHomeCare extends Model
{
    //
    protected $fillable = [
        'work_day_id',
        'date',
        'is_active',
    ];

    protected $casts = [
        'date' => 'datetime:H:i:s',
    ];



    public function period_work_day()
    {
        return $this->belongsTo(WorkDay::class , 'work_day_id');
    }


}
