<?php

namespace App\Enums\Appointments\appointment;

enum AppointmentStatus :string
{

    case Confirmed ='Confirmed';
    case Occur     ='Occur';
    case Don       ='Don';
    case Missed     ='Missed';
    case Cancel    ='Cancel';


    public function label(): string
    {
        return match($this) {
            self::Confirmed => 'مؤكد',
            self::Occur => 'جاري',
            self::Don => 'انتهى',
            self::Missed=>"ضاع",
            self::Cancel=>'ألغي',
        };
    }




}
