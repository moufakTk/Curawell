<?php

namespace App\Enums\Payments;

enum BallStatus :string
{
    //

    case Complete = 'Complete';
    case Incomplete = 'Incomplete';
    case Canceled = 'Canceled';


    public function label(): string
    {
        return match ($this) {
            self::Complete =>'Complete' ,
            self::Incomplete =>'Incomplete',
            self::Canceled =>'Canceled',

        };
    }

}
