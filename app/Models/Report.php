<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //

    protected $fillable = [
        'reportable',
        'file_path',
    ];


    protected function filePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? asset('storage/' . $value) : null,
            );
        }


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
