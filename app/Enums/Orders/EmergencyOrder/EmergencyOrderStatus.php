<?php

namespace App\Enums\Orders\EmergencyOrder;

enum EmergencyOrderStatus :string
{
    //
    case InProgress = 'InProgress';
    case Completed  = 'Completed';
    case Canceled     = 'Canceled';


    public function label(): string
    {
        return match ($this) {
            self::InProgress =>'InProgress',
            self::Canceled =>'Canceled',
            self::Completed =>'Completed'   ,
        };
    }


}
