<?php

namespace App\Http\Resources\Admin\Articles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'title_en'             => $this->title_en,
            'title_ar'             => $this->title_ar,
            'brief_description_en' => $this->brief_description_en,
            'brief_description_ar' => $this->brief_description_ar,
            'link'            => $this->path_link,
            'is_active'            => (bool) $this->is_active,
            // علاقة الصورة (image)
            'image' => $this->whenLoaded('image', function () {
                return [
                    'id'  => $this->image->id,
                    'url' => $this->image->url, // accessor من موديل Image
                    'type'=> $this->image->type,
                ];
            }),
        ];
    }
}
