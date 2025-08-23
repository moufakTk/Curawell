<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetenceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->service_id,
            'service' => new ServiceMiniResource($this->whenLoaded('competence_services')),
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'brief_description_en' => $this->brief_description_en, // array (casted)
            'brief_description_ar' => $this->brief_description_ar, // array (casted)
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
