<?php

namespace App\Enums\Appointments;

enum AppointmentHomeCareType :string
{
    //
    case CheckOut = 'General Medical Checkup';
    case Physical = 'Physical Therapy';
    case Sample   = 'Sample Collection';


    public function label(): string
    {
        return match ($this) {
            self::CheckOut   => 'General Medical Checkup' ,
            self::Physical => 'Physical Therapy' ,
            self::Sample     => 'Sample Collection',
        };
    }

}
