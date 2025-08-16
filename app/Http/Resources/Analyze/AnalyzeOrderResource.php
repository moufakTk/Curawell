<?php

namespace App\Http\Resources\Analyze;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyzeOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'doctor_id'       => $this->doctor_id,
            'name'       => $this->name,
            'status'     => $this->status->value ?? $this->status,
            'price'      => $this->price,
            'patient'    => [
                'id'   => $this->patient_id,
                'name' => optional($this->analyzed_order_patient?->patient_user)->full_name,
            ],

            // عناصر التحاليل المرتبطة
            'analyzes' => $this->whenLoaded('AnalyzeRelated', function () {
                return $this->AnalyzeRelated->map(function ($row) {
                    return [
                        'id'         => $row->id,
                        'analyze_id' => $row->analyze_id,
                        'analyze_name'    => $row->relationLoaded('analyzesRelated_analyze') ? $row->analyzesRelated_analyze->{'name_' . app()->getLocale()}
                            : null,
                        'price'    => $row->relationLoaded('analyzesRelated_analyze') ? $row->analyzesRelated_analyze->price : null,

                    ];
                });
            }),

            // العينات المرتبطة
            'samples' => $this->whenLoaded('samplesRelated', function () {
                return $this->samplesRelated->map(function ($row) {
                    return [
                        'sample_id' => $row->sample_id,
                        'sample_type' => $row->SamplesRelated_sample->sample_type,

                    ];
                });
            }),
            'reports' => $this->whenLoaded('reports', function () {
                return $this->reports->map(function ($row) {
                    return [
                        'report_id' => $row->id,
                        'file_path' => $row->file_path,

                    ];
                });
            }),
        ];
    }
}
