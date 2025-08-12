<?php

namespace Database\Factories;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Appointments\appointment\AppointmentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'phone_number'=>fake()->phoneNumber(),
            'status'=>fake()->randomElement(AppointmentStatus::cases())->value,
            'appointment_type'=>AppointmentType::Electronically,
        ];
    }
}
