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
    case driver    ='driver';
    case Patient   ='Patient';


    public function label(): string
    {
        return match ($this) {
            self::Admin =>'Admin',
            self::Doctor =>'Doctor',
            self::Nurse =>'Nurse',
            self::Secretary =>'Secretary',
            self::Reception =>'Reception',
            self::driver   =>'driver',
            self::Patient  =>'Patient',

        };
    }
}
