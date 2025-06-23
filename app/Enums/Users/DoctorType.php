<?php

namespace App\Enums\Users;

enum DoctorType :string
{
    //

    case Clinic       = 'Clinic';
    case Laboratory   = 'Laboratory';
    case Radiographer = 'Radiographer';
    case Relief       = 'Relief';

    public function label(): string
    {
        return match ($this) {
            self::Clinic =>'Clinic',
            self::Laboratory =>'Laboratory',
            self::Radiographer =>'Radiographer',
            self::Relief =>'Relief',
        };
    }
}
