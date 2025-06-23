<?php

namespace App\Enums\Orders\EmergencyOrder;

enum EmergencyOrderType :string
{
    //
    case Call          = 'Call';
    case FaceToFace    = 'FaceToFace';
    case NonEmergency  = 'NonEmergency';


    public function label(): string
    {
        return match ($this) {
            self::Call => 'Call',
            self::FaceToFace => 'FaceToFace',
            self::NonEmergency => 'NonEmergency',
        };
    }
}
