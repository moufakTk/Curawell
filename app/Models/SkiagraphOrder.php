<?php

namespace App\Models;

use App\Enums\Orders\SkiagraphOrderStatus;
use Illuminate\Database\Eloquent\Model;

class SkiagraphOrder extends Model
{
    //

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'doctor_name',
        'price',
        'status',
    ];

    protected $casts = [
        'status'=>SkiagraphOrderStatus::class,
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */


    public function skaigraph_patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function skaigraph_doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /*
     * Morph PK
     */

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

}
