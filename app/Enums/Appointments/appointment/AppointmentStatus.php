<?php

namespace App\Enums\Appointments\appointment;

enum AppointmentStatus :string
{

    case Confirmed ='Confirmed';
    case Occur     ='Occur';
    case Don       ='Don';
    case Cancel    ='Cancel';


    public function label(): string
    {
        return match($this) {
            self::Confirmed => 'مؤكد',
            self::Occur => 'جاري',
            self::Don => 'انتهى',
            self::Cancel=>'ألغي',
        };
    }




}
