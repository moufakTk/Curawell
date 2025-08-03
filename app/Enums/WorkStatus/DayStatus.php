<?php

namespace App\Enums\WorkStatus;

enum DayStatus :string
{
    //
    case ACTIVE  = 'Active';
    case UNACTIVE = 'UnActive';
    case RESERVED = 'Reserved';
    case FORBIDDEN = 'Forbidden';



    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::UNACTIVE  => 'UnActive',
            self::RESERVED  => 'Reserved',
            self::FORBIDDEN => 'Forbidden',

        };
    }

}
