<?php

namespace App\Enums\Appointments\Waiting;

use function Laravel\Prompts\select;

enum WaitingType: string
{
    //

    case Emergency = 'Emergency';
    case Disabled  = 'Disabled';
    case Old       = 'Old';


    public function label(): string
    {
        return match ($this) {
            self::Emergency =>'طارئ',
            self::Disabled=>'مقعد',
            self::Old=>'كبير سن' ,
        };
    }
}
