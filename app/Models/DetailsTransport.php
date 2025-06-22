<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailsTransport extends Model
{
    //

    protected $fillable = [
        'relife_order_id',
        'ambulance_team_id',
        'location_en',
        'location_ar',
        'price_transport',
    ];


    /*
     * who has my PK
    */


    /*
     * my FK belongs to
    */

    public function details_relife_order()
    {
        return $this->belongsTo(RelifeOrder::class , 'relife_order_id');
    }

    public function details_ambulance_team()
    {
        return $this->belongsTo(Ambulance_team::class , 'ambulance_team_id');
    }
}
