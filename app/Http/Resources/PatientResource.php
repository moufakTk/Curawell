<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $locale;
    protected $kind;
    public function __construct($resource ,$kind=null)
    {
        parent::__construct($resource);
        $this->locale = app()->getLocale();
        $this->kind=$kind;
    }

    public function toArray(Request $request): array
    {
        return [
            "user_id"=>$this->user_id,
            'patient_id'=>$this->id,
            'patient_num'=>$this->patient_num,
            'name'=>$this->getFullNameAttribute(),
            'age'=>optional($this->patient_user)->age,
            'phone'=>optional($this->patient_user)->phone,
            'gender'=>optional($this->patient_user)->gender,
            'first_visit'=>($this->kind =='secretary')?optional($this->patient_user)->created_at->format('Y-m-d H:i'):null,

        ];

    }
}
