<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function __construct($resource)
    {
        parent::__construct($resource);
        //$this->locale=app()->getLocale();
    }

    public function toArray(Request $request): array
    {
        return match ($request->route()->getName()) {
            'patient.index' => [
                'user_id'    => $this->id,
                'name_patient'=>$this->first_name.' '.$this->last_name,

                //'desc'  => $this->description,
                //'image' => $this->image_url,
            ],

            'doctors.index' => [
                'user_id'=>$this->id,
                'name_doctor'=>$this->first_name.' '.$this->last_name,
            ],

            default => [
                'id'   => $this->id,
                //'name' => $this->name,
            ]
        };
    }
}
