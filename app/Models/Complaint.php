<?php

namespace App\Models;

use App\Enums\StylReplyOfComplaint;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    //

    protected $fillable = [
        'patient_id',
        "complaint",
        'styl_reply',
        'email',
        'phone',
        'reply',
        'message_reply',
        'status',
    ];


    protected $casts = [
        'styl_reply'=>StylReplyOfComplaint::class,
    ];


    public function complaint_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


}
