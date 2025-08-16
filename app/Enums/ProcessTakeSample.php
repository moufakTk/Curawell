<?php

namespace App\Enums;

enum ProcessTakeSample :string
{
    //
    case HomeCare         ='HomeCare';
    case External         = 'External';

    public function label(): string
    {
        return match ($this) {
            self::HomeCare =>'HomeCare',
            self::External =>'External'

        };
    }
}
