<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //

    protected $fillable = [];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */



    /*
     * Morph FK
     */

    public function reportable()
    {
        return $this->morphTo();
    }


}
