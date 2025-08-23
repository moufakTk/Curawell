<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Doctor;
use App\Models\Section;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        $baseData = [
            'id' => $this->id,
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
            'patient' => [
                'id' => $this->comment_patient->id ?? null,
                'name' => $this->comment_patient->patient_user->full_name ?? 'مستخدم مجهول',
                'phone' => $this->comment_patient->patient_user->phone ?? null,
            ],
        ];

        // إضافة بيانات حسب نوع الموديل
        $typeData = $this->getTypeSpecificData();

        return array_merge($baseData, $typeData);
    }

    protected function getTypeSpecificData()
    {
        // تعليق على دكتور
        if ($this->commentable_type === Doctor::class && $this->commentable) {
            return [
                'type' => 'doctor',
                'doctor' => [
                    'doctor_id' => $this->commentable->id,
                    'name' => $this->commentable->doctor_user->full_name ?? null,
//                    'specialization' => $this->commentable->doctor_user->work_location->locationable->name_en ?? null,
//                    'image' => $this->commentable->image_url ?? null,
                    'rating' => $this->commentable->evaluation ?? 0,
                ],

            ];
        }

        // تعليق على قسم
        if ($this->commentable_type === Section::class && $this->commentable) {
            return [
                'type' => 'section',
                'target' => [
                    'id' => $this->commentable->id,
                    'name' => $this->commentable->name_en,
                ],

            ];
        }

        // تعليق عام (بدون موديل مرتبط)
        if ($this->commentable_type === null) {
            return [
                'type' => 'general',
                'target' => [
                    'name' => 'تعليق عام',
                    'description' => 'تعليق على المركز بشكل عام',
                ],
                            ];
        }

        // نوع غير معروف (fallback)
        return [
            'type' => 'unknown',
            'target' => [
                'name' => 'غير معروف',
                'description' => 'نوع التعليق غير معروف',
            ],
        ];
    }
}
