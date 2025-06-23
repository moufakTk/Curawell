<?php

namespace App\Enums;

enum SampleType :string
{
    //
    case Blood         ='Blood';
    case Gland         = 'Gland';
    case Urinalysis    = 'Urinalysis';
    case StoolAnalysis = 'StoolAnalysis';
    case Biopsy        = 'Biopsy';


    public function label(): string
    {
        return match ($this) {
            self::Blood =>'Blood',
            self::Gland =>'Gland',
            self::Urinalysis =>'Urinalysis',
            self::StoolAnalysis =>'StoolAnalysis',
            self::Biopsy =>'Biopsy',

        };
    }
}
