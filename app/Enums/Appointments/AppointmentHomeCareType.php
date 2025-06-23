<?php

namespace App\Enums\Appointments;

enum AppointmentHomeCareType :string
{
    //
    case CheckOut = 'CheckOut';
    case Physical = 'Physical';
    case Sample   = 'Sample';


    public function label(): string
    {
        return match ($this) {
            self::CheckOut   => 'CheckOut' ,
            self::Physical => 'Physical' ,
            self::Sample     => 'Sample',
        };
    }

}
