<?php

namespace App\Enums\WorkStatus;

enum PeriodStatus :string
{
    //
    case ACTIVE  = 'Active';
    case UNACTIVE = 'UnActive';

    case FORBIDDEN = 'Forbidden';



    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::UNACTIVE  => 'UnActive',
            self::FORBIDDEN => 'Forbidden',

        };
    }

}
