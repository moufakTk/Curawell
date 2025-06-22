<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'patient_id',
        'commentable',
        'comment_en',
        'comment_ar',
        'status',
    ];

    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function comment_patient()
    {
        return $this->belongsTo(Patient::class , 'patient_id');
    }


    /*
     * Morph (nullable)  FK
     */

    public function commentable()
    {
        return $this->morphTo();
    }
}
