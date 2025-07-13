<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopDoctorResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
        //$this->locale=app()->getLocale();
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_id'=>$this->id,
            'doctor_info'=> new UserInfoResource($this->whenLoaded('doctor_user')),
            'evaluation'=>$this->evaluation

        ];

    }
}
