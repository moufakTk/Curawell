<?php

namespace App\Enums\Services;

enum SectionType:string
{
    //
    case Clinics            ='Clinics';
    case HomeCare           ='HomeCare';
    case LaboratoryAnalysis = 'LaboratoryAnalysis';
    case Radiography        = 'Radiography';
    case Emergency          = 'Emergency';


    public function label(): string
    {
        return match ($this) {
            self::Clinics => 'Clinics',
            self::HomeCare  => 'HomeCare',
            self::LaboratoryAnalysis  => 'LaboratoryAnalysis',
            self::Radiography => 'Radiography',
            self::Emergency => 'Emergency',
        };
    }


}
