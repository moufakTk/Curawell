<?php
namespace Database\Factories;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Models\AppointmentHomeCare;
use App\Models\Patient;
use App\Models\NurseSession;
use App\Enums\Appointments\AppointmentHomeCareType;
use App\Enums\Gender;
use App\Enums\Sessions\SessionNurseStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentHomeCareFactory extends Factory
{
    protected $model = AppointmentHomeCare::class;

    public function definition(): array
    {
        // جلب أول جلسة "Available"
        $session = NurseSession::where('status', SessionNurseStatus::Available)
            ->orderBy('id')
            ->first();

//        if ($session) {
//            // تعديل حالتها إلى Unavailable (أو Reserved مثلاً)
//            $session->update([
//                'status' => SessionNurseStatus::Reserved,
//            ]);
//        }

        return [
            'patient_id'       => Patient::inRandomOrder()->first()?->id,
            'nurse_session_id' => $session?->id, // ممكن تكون null لو ما في جلسات
            'type'             => fake()->randomElement(AppointmentHomeCareType::cases()),
            'gender'           => fake()->randomElement(Gender::cases()),
            'location'         => fake()->address(),
            'phone_number'     => fake()->phoneNumber(),
            'status'          =>fake()->randomElement(AppointmentHomeCareStatus::cases()),
            'notes'            => fake()->sentence(),
//            'price'            => null,
//            'explain'          => null,
        ];
    }
}
