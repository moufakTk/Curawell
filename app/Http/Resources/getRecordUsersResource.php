<?php

namespace App\Http\Resources;

use App\Enums\Users\DoctorType;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getRecordUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $massages=[];

    public function __construct($resource, $massages)
    {
        parent::__construct($resource);
        $this->massages = $massages;
    }
    public function toArray(Request $request): array
    {


        return [

            'success'  => true,

            'data'=>[
                'patients'=>$this['patient'] ,
                'nurses'=>$this['nurse'] ,
                'doctors'=>$this['doctor'] ,
                'doctor_typs'=> $this->when($this['doctor'] > 0, function () {
                    return [
                        'clinic' => Doctor::where('doctor_type', DoctorType::Clinic)->count(),
                        'Laboratory'    => Doctor::where('doctor_type', DoctorType::Laboratory)->count(),
                        'Radiographer'    => Doctor::where('doctor_type', DoctorType::Radiographer)->count(),
                        'relief' => Doctor::where('doctor_type', DoctorType::Relief)->count(),
                    ];
                }),
            ],

            'messages' => !empty($this->massages) ? $this->massages : __('messages.all_have_data'),

        ];






    }
}
