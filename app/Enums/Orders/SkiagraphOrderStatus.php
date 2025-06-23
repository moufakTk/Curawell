<?php

namespace App\Enums\Orders;

enum SkiagraphOrderStatus:string
{
    //

    case InPreparation = 'Inpreparation';
    case Prepared     = 'Prepared';
    case Canceled       = 'Canceled';


    public function label(): string
    {
        return match ($this) {
            self::InPreparation =>'InPreparation' ,
            self::Prepared =>'Prepared' ,
            self::Canceled =>'Canceled',
        };
    }
}
