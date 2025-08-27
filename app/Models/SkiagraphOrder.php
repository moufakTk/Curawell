<?php

namespace App\Models;

use App\Enums\Orders\SkiagraphOrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkiagraphOrder extends Model
{
    use HasFactory;
    //


    protected $fillable = [
        'bill_num',
        'patient_id',
        'doctor_id',
        'small_service_id',
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
public function skaigraph_small_service(){
        return $this->belongsTo(SmallService::class, 'small_service_id');
}
    /*
     * Morph PK
     */

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }



    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

}
