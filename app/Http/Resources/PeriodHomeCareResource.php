<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeriodHomeCareResource extends JsonResource
{

    protected $locale;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {



        return

            match ($request->route()->getName()) {

                'period_to_patient' =>[

                    'name'=>$this->getLocalized('day'),
                    'date'=>$this->history,
                    'status'=>$this->status,
                    'periods_for_day' => $this->periods->map(function ($period) {
                        return [
                            'id' => $period->id,
                            'time' => \Carbon\Carbon::parse($period->date)->format('H:i:s'),
                            'status' => $period->is_active,
                        ];
                    }),
                ],


            };





    }

    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale();
        return $this->{"{$key}_{$locale}"} ?? null;
    }
}
