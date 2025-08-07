<?php

namespace App\Enums\Appointments\appointment;

enum AppointmentHomeCareStatus: string
{
    case Scheduled   = 'Scheduled';
    case InProgress  = 'InProgress';
    case Completed   = 'Completed';
    case Cancelled   = 'Cancelled';
    case Missed      = 'Missed';

    public function label(): string
    {
        return match ($this) {
            self::Scheduled   => 'مجدول',
            self::InProgress  => 'جاري الآن',
            self::Completed   => 'مكتمل',
            self::Cancelled   => 'ملغى',
            self::Missed      => 'فائت',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Scheduled   => 'blue',
            self::InProgress  => 'orange',
            self::Completed   => 'green',
            self::Cancelled   => 'red',
            self::Missed      => 'gray',
        };
    }
}
