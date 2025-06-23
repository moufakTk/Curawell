<?php

namespace App\Enums\Orders\EmergencyOrder;

enum EmergencyOrderDestination :string
{
    //
    case MedicalCenter   ='MedicalCenter' ;
    case AlHiaHospital   ='AlhiaHospital' ;
    case AlHilalHospital ='AlhilalHospital' ;
    case UnKnown         ='UnKnown' ;


    public function label(): string
    {
        return match ($this) {
            self::MedicalCenter =>'MedicalCenter' ,
            self::AlHiaHospital =>'AlhiaHospital' ,
            self::AlHilalHospital =>'AlhilalHospital' ,
            self::UnKnown =>'UnKnown' ,
        };
    }

}
