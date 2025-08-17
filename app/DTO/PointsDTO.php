<?php

namespace App\DTO;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
final class PointsDTO implements Arrayable, \JsonSerializable
{
    public function __construct(
        public readonly int $sum_points,
        public readonly int $sum_point_replaced,
        /** @var Collection<\App\Models\UserPoint> */
        public readonly Collection $points,
        /** @var Collection<\App\Models\UserReplacement> */
        public readonly Collection $points_replaced,
    ) {}


    public function toArray(): array
    {
        return [
            'sum_points'         => $this->sum_points,
            'sum_point_replaced' => $this->sum_point_replaced,
            'points'             => $this->points,
            'points_replaced'    => $this->points_replaced,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }


}
