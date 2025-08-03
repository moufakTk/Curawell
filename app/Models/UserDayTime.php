<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDayTime extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_en',
        'day_ar',
        'timeStart',
        'timeEnd',
    ];



    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }




}
