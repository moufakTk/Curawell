<?php

namespace Database\Factories;

use App\Models\AnalyzeOrder;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Analyze;
use App\Models\Sample;
use App\Enums\Orders\AnalyzeOrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyzeOrderFactory extends Factory
{
    protected $model = AnalyzeOrder::class;

    public function definition(): array
    {
        return [
            'patient_id'   => 1,
            'doctor_id'    => null,
            'name'         => $this->faker->word,
            'doctor_name'  => $this->faker->name,
            'status'       => AnalyzeOrderStatus::Pending,
            'price'        => 0, // بنحسب السعر حسب التحاليل المضافة
            'sample_type'  => $this->faker->randomElement(['Blood', 'Urine']),
        ];
    }

    /**
     * مع التحاليل المرتبطة
     */
    public function withAnalyses(int $count = 3)
    {
        return $this->afterCreating(function (AnalyzeOrder $order) use ($count) {
            $totalPrice = 0;

            for ($i = 0; $i < $count; $i++) {
                $analyze = Analyze::factory()->create();
                $order->AnalyzeRelated()->create([
                    'analyze_id' => $analyze->id,
                    'price'      => $analyze->price,
                ]);
                $totalPrice += $analyze->price;
            }

            $order->update(['price' => $totalPrice]);
        });
    }

    /**
     * مع العينات المرتبطة
     */
    public function withSamples(int $count = 2)
    {
        return $this->afterCreating(function (AnalyzeOrder $order) use ($count) {
            for ($i = 0; $i < $count; $i++) {
                $sample = Sample::factory()->create(['patient_id' => $order->patient_id]);
                $order->samplesRelated()->create([
                    'sample_id' => $sample->id,
                ]);
                $sample->status=0;
                $sample->save();
            }
        });
    }
}
