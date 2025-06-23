<?php

namespace App\Enums\Sessions;

enum SessionNurseStatus :string
{
    //
    case Available   = 'Available';
    case Reserved    = 'Reserved';
    case UnAvailable = 'UnAvailable';
    case TurnOff     = 'TurnOff';




    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available' ,
            self::Reserved  => 'Reserved',
            self::UnAvailable=> 'UnAvailable',
            self::TurnOff  => 'TurnOff',
        };
    }

}
