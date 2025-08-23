<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'section_id'  => $this->section_id,
            'section'     => new SectionMiniResource($this->whenLoaded('service_section')),
            'name_en'     => $this->name_en,
            'name_ar'     => $this->name_ar,
            'details_services_en' => $this->details_services_en, // array (casted)
            'details_services_ar' => $this->details_services_ar, // array (casted)
            'competences_count'   => $this->whenCounted('competences'),
            'created_at'  => $this->created_at?->toIso8601String(),
            'updated_at'  => $this->updated_at?->toIso8601String(),
            'image' => $this->whenLoaded('image', fn() => [
                'id' => $this->image->id,
                'url'=> $this->image->url,
            ]),
            'video' => $this->whenLoaded('video', fn() => [
                'id' => $this->video->id,
                'url'=> $this->video->url,
            ]),
        ];
    }
}
