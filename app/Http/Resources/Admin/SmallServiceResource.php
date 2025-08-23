<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SmallServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'section_id'     => $this->section_id,
            'section'        => new SectionMiniResource($this->whenLoaded('smallService_section')),
            'name_en'        => $this->name_en,
            'name_ar'        => $this->name_ar,
            'price'          => $this->price,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'divisions_count'=> $this->whenCounted('divisions'),
            'what_photos_count' => $this->whenCounted('whatPhotos'),
            'created_at'     => $this->created_at?->toIso8601String(),
            'updated_at'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
