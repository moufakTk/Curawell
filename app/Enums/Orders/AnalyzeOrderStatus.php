<?php

namespace App\Enums\Orders;

enum AnalyzeOrderStatus :string
{
    //
    case InProgress = "InProgress";
    case InPreparation = "InPreparation";
    case Prepared = "Prepared";
    case Canceled = "Canceled";

    public function label(): string
    {
        return match ($this) {
            self::InProgress =>'InProgress' ,
            self::InPreparation =>'InPreparation' ,
            self::Prepared =>'Prepared' ,
            self::Canceled =>'Canceled',
        };
    }
}
