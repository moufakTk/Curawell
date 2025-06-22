<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'point_id',
        'pointable',
        'history',
        'point_number',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function point_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }
    public function point_point()
    {
        return $this->belongsTo(Point::class , 'point_id');
    }

    /*
     * Morph (nullable)  FK
     */

    public function pointable()
    {
        return $this->morphTo();
    }
}
