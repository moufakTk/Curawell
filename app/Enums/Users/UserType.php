<?php

namespace App\Enums\Users;

enum UserType :string
{
    //

    case Admin     ='Admin';
    case Doctor    ='Doctor';
    case Nurse     ='Nurse';
    case Secretary ='Secretary';
    case Reception ='Reception';
    case Driver    ='Driver';
    case Patient   ='Patient';


    public function label(): string
    {
        return match ($this) {
            self::Admin =>'Admin',
            self::Doctor =>'Doctor',
            self::Nurse =>'Nurse',
            self::Secretary =>'Secretary',
            self::Reception =>'Reception',
            self::Driver   =>'Driver',
            self::Patient  =>'Patient',

        };
    }


    public function defaultRole(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            //self::Doctor => 'doctor',
            self::Nurse => 'Nurse',
            self::Secretary => 'Secretary',
            self::Driver => 'Driver',
            self::Reception => 'Reception',
            self::Patient => 'Patient',
        };
    }

}
