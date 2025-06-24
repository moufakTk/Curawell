<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReplacement extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'replacement_id',
        'replacement_time',
        'replace_point_num',

    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function user_rep_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user_rep_replacement()
    {
        return $this->belongsTo(Replacement::class, 'replacement_id');
    }
}
