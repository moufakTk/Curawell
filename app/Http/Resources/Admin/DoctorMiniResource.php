<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorMiniResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->doctor_user->full_name ?? ($this->user->full_name ?? null), // عدّل حسب بنية Doctor عندك
            'doctor_type' => $this->doctor_type ?? null, // اختياري
        ];
    }
}
