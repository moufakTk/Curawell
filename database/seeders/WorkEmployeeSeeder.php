<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Enums\Sessions\SessionDoctorStatus;
use App\Enums\Sessions\SessionNurseStatus;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Enums\WorkStatus\PeriodStatus;
use App\Models\DoctorSession;
use App\Models\NurseSession;
use App\Models\Section;
use App\Models\User;
use App\Models\WorkDay;
use App\Models\WorkEmployee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WorkEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $user_id = User::where('user_type', UserType::Doctor)
            ->whereHas('doctor', function ($q) {
                $q->where('doctor_type',DoctorType::Clinic );
            })
            ->with('doctor')
            ->get();

        $user_nurse=User::where('user_type',UserType::Nurse)->whereHas('work_location',function($q){
            $q->where(['locationable_type'=>Section::class, 'locationable_id'=>Section::where('section_type',SectionType::HomeCare)->value('id')]);
        })->with('work_location')
            ->get();

        $time_period=['09:00:00','15:00:00','18:00:00'];

        WorkDay::whereNotIn('day_en',['Friday','Saturday'])->each(function ($workDay) use ($user_id ,$user_nurse,$time_period) {

            //قيم لايام الطبيب والفترات فيها//
            foreach ($user_id as $id) {
                $wde=WorkEmployee::factory()->create([
                    'work_day_id' => $workDay->id,
                    'user_id' => $id->id,
                    'status'=>($id->doctor->hold_end < $workDay->history) ? PeriodStatus::UNACTIVE : PeriodStatus::ACTIVE,
                ]);

                $wde_start =Carbon::parse($wde->from);
                $wde_end =  Carbon::parse($wde->to);

                while ($wde_start->lt($wde_end)) {
                    $slotStart = $wde_start->copy();
                    $slotEnd = $wde_start->copy()->addMinutes(20);

                    if ($slotEnd->gt($wde_end)) {
                        break; // ما منكمّل إذا طلع خارج وقت الدوام
                    }

                    DoctorSession::create([
                        'work_employee_id' => $wde->id,
                        'from' => $slotStart->format('H:i'),
                        'to' => $slotEnd->format('H:i'),
                        'status'=>($wde->status === PeriodStatus::ACTIVE)? SessionDoctorStatus::Available : SessionDoctorStatus::UnAvailable ,
                    ]);
                    $wde_start->addMinutes(20);
                }
            }
            //.................//

            //قيم لفترات الخدمة المزلية يوميا وفترات الممرضين ايضا//

            foreach ($user_nurse as $item){
                $we=WorkEmployee::create([
                    'work_day_id'=>$workDay->id,
                    'user_id'=>$item->id,
                    'status'=>PeriodStatus::ACTIVE,
                    'from'=>$time_period[0],
                    'to'=>$time_period[count($time_period)-1],
                ]);


                foreach ($time_period as $time){
                    NurseSession::create([
                        'work_employee_id'=>$we->id,
                        'status'=>SessionNurseStatus::Available,
                        'time_in'=>$time
                    ]);
                }
            }

            //............................//

        });



    }
}
