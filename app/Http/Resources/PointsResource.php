<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointsResource extends JsonResource
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

        /** @var \App\DTO\PointsDTO $dto */
        $dto = $this->resource;

        return [
            'sum_points' =>$dto->sum_points,
            'sum_point_replaced'=>$dto->sum_point_replaced,
            'points'=>$dto->points->map(function ($point) {
                return [
                    'history' => $point->history,
                    'point_number' => $point->point_number,
                    'reason' => $point->point_point->{'name_' . $this->locale},
                ];
            }),
            'points_replaced'=>$dto->points_replaced->map(function ($point) {
                return [
                    'replace_point_num'=>$point->replace_point_num,
                    'replacement_time'=>$point->replacement_time,
                    'reason'=>$point->user_rep_replacement->{'description_' . $this->locale},
                ];
            }),

        ];
    }

    protected function getLocalized(string $key): ?string
    {
        $locale = app()->getLocale();
        return $this->{"{$key}_{$locale}"} ?? null;
    }
}
