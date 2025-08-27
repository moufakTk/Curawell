<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillAnalyzeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $locale;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
    }

    public function toArray(Request $request): array
    {
        return [
            'bill_num'=>$this->bill_num,
            'doctor_name'=>$this->doctor_name,
            'date'=>$this->created_at->format('Y-m-d H:i'),
            'name'=>$this->name,
            'price'=>$this->price,
            'department'=>$this->department,
            'analyzes_name'=>$this->AnalyzeRelated->map(function ($analyze) {
                $related = $analyze->analyzesRelated_analyze;
                return $related ? $related->{'name_'.$this->locale} : null;
            })

        ];
    }
}
