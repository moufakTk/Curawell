<?php

namespace App\Enums\Orders;

enum AnalyzeOrderStatus :string
{
    //
    case Pending = "Pending";
    case Accepted = "Accepted";
    case InProgress = "Prepared";
    case Completed = "Completed";
    case Canceled = "Canceled";

    public function label(): string
    {
        return match ($this) {
            self::InProgress =>'InProgress' ,
            self::Pending =>'Pending' ,
            self::Accepted =>'Accepted' ,
            self::Completed =>'Completed',
            self::Canceled =>'Canceled',
        };
    }
}
