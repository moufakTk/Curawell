<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillRadiologyResource extends JsonResource
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
            'price'=>$this->price,
            'date'=>$this->created_at,
            'name'=>optional($this->skaigraph_small_service)->{'name_'.$this->locale},

        ];
    }
}
