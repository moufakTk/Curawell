<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'small_service_id'=> $this->small_service_id,
            'small_service'   => new SmallServiceResource($this->whenLoaded('division_small_service')),
            'doctor_id'       => $this->doctor_id,
            'doctor'          => new DoctorMiniResource($this->whenLoaded('division_doctor')),
            'is_discounted'   => (bool)$this->is_discounted,
            'discount_rate'   => (float)$this->discount_rate,
            'treatments_count'=> $this->whenCounted('treatments'),
            'created_at'      => $this->created_at?->toIso8601String(),
            'updated_at'      => $this->updated_at?->toIso8601String(),
        ];
    }
}
