<?php

namespace App\Enums\Appointments\appointment;

enum AppointmentType :string
{
    //
    case Electronically ='Electronically';
    case FaceToFace     = 'FaceToFace';
    case Point          = 'Point';


    public function label(): string
    {
        return match ($this) {
            self::Electronically => 'Electronically' ,
            self::FaceToFace     => 'FaceToFace' ,
            self::Point          => 'Point' ,
        };

    }

}
