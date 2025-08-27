<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillHCResource extends JsonResource
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
            'type'=>$this->type,
            'price'=>$this->price,
            'date'=>$this->created_at->format('Y-m-d H:i'),
            'nurse'=>$this->nurse,

        ];
    }
}
