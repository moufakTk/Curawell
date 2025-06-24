<?php

namespace App\Enums\Sessions;

enum SessionCenterType :string
{
    //
    case Relife ='Relief';
    case Clinic = 'Clinic';


    public function label(): string
    {
        return match ($this) {
            self::Relife =>'Relief',
            self::Clinic =>'Clinic',
        };
    }
}
