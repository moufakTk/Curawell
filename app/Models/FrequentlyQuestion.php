<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequentlyQuestion extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'question_en',
        'question_ar',
        'answer_en',
        'answer_ar',
        'status',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */
}
