<?php

namespace Database\Factories;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Enums\Orders\SkiagraphOrderStatus;
use App\Enums\Services\SectionType;
use App\Models\Section;
use App\Models\SmallService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SkiagraphOrder>
 */
class SkiagraphOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $section=  Section::where('section_type',SectionType::Radiography)->first();
        $service=SmallService::where('section_id',$section->id)->first();

             return [
                 'patient_id'=>1,
                 'small_service_id'=>$service->id,
                 'doctor_name'=>"Asdfasdfaf",
                 'price'=>$service->price,
                 'status'=>fake()->randomElement(SkiagraphOrderStatus::cases())->value,
             ];
    }
}
