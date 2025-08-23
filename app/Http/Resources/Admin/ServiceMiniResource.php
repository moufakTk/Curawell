<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceMiniResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'section_id' => $this->section_id,
        ];
    }
}
