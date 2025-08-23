<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "patient_id" => $this->patient_id,
            "complaint" => $this->complaint,
            "styl_reply" => $this->styl_reply,
            "email" => $this->email,
            "phone" => $this->phone,
            "reply" => $this->reply,
            "message_reply" => $this->message_reply,
            "created_at" => $this->created_at,
            "patient_name" => $this->complaint_patient->patient_user->full_name,
            "patient_num" => $this->complaint_patient->patient_num,

        ];
    }
}
