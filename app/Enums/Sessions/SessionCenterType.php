<?php

namespace App\Enums\Sessions;

enum SessionCenterType :string
{
    //
    case Relife ='Relife';
    case Clinic = 'Clinic';


    public function label(): string
    {
        return match ($this) {
            self::Relife =>'Relife',
            self::Clinic =>'Clinic',
        };
    }
}
