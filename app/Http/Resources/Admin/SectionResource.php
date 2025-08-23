<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'brief_description_en' => $this->brief_description_en,
            'brief_description_ar' => $this->brief_description_ar,
            'section_type' => $this->section_type?->value,
            'section_type_label' => $this->section_type?->label(),
            'services_count' => $this->whenCounted('services'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'image' => $this->whenLoaded('image', fn() => [
                'id' => $this->image->id,
                'url'=> $this->image->url,
            ]),
        ];
    }
}
