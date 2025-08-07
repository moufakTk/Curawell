<?php

namespace App\Services\Appointment;

use App\Enums\Services\SectionType;
use App\Enums\Sessions\SessionNurseStatus;
use App\Enums\Users\UserType;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\Assigned;
use App\Models\Doctor;
use App\Models\PeriodHomeCare;
use App\Models\Point;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\WorkDay;
use App\Models\WorkLocation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class HomeCareService
{

    protected $locale;
    public function __construct()
    {
        $this->locale=app()->getLocale();
    }

   public function services($request)  //هذا التابع ليس فقط للخدمة المنزلية بل لكل الاقسام التي فيها خدمات لنرجعهامثلا العيادات
   {
     $service= Service::where('section_id',$request->section_id)
         ->select('id','name_'.$this->locale,'details_services_'.$this->locale)
         ->get();
     if($service->isEmpty()){
         return [
             'success'=>false,
             'message'=>__('messages.services_not_found'),
             'data'=>[]
         ];
     }
     return [
         'success'=>true,
         'message'=>__('messages.services_found'),
         'data'=>$service
     ];

   }

    public function nurseHomeCare()
    {
        $nurses=User::where('user_type',UserType::Nurse)
            ->whereHas('work_location' ,function ($q) {
                $q->where([
                    'locationable_type' => Section::class,
                    'locationable_id' => Section::where('section_type', SectionType::HomeCare)->value('id')
                ]);
            })->select('id','first_name','last_name','gender','phone')
            ->orderBy('gender')
            ->get();

        if($nurses->isEmpty()){
            return [
                'success'=>false,
                'message'=>__('messages.nurse_not_exist'),
                'data'=>[]
            ];
        }

        return [
            'success'=>true,
            'message'=>__('messages.nurse_exist'),
            'data'=>$nurses
        ];
    }

    public function periodsHomeCare($request)
    {

        $work_day= WorkDay::where('history',$request->date)
            ->select('id','day_'.$this->locale,'history','status')
            ->with('periods')
            ->first();

       return $work_day ;

    }

    public function reserveAppointmentHomeCare($request){

        $registered = DB::transaction(function () use ($request) {

            $user=User::where('id',auth()->user()->id)->with('patient')->first();
            $nurses=User::where(['user_type'=>UserType::Nurse ,'gender'=>$request->gender])
                ->whereHas('work_location' ,function ($q) {
                    $q->where([
                        'locationable_type' => Section::class,
                        'locationable_id' => Section::where('section_type', SectionType::HomeCare)->value('id')
                    ]);
                })->with(['work_employees'=>function($q)use($request){$q->where('work_day_id',WorkDay::where('history' ,$request->date)->value('id')) ->with('nurse_sessions');}])
                ->orderBy('num_patients')
                ->get();

            if($nurses->isEmpty()){
                return [
                    'success'=>false,
                    'message'=>__('messages.nurse_gender_not_exist'),
                    'data'=>[]
                ];
            }

            $period=PeriodHomeCare::where('id',$request->period_id)->whereHas('period_work_day', function ($q) use ($request) {
                $q->where('history', $request->date);
            })->with('period_work_day')->first();
            if(!$period){
                return [
                    'success'=>false,
                    'message'=>__('messages.period_not_for_history'),
                    'data'=>[]
                ];
            }


            $date=$period->period_work_day->history;
            $time=$period->date->format('H:i:s');
            $periodDateTime = Carbon::parse("$date $time");

            if(now()->greaterThanOrEqualTo($periodDateTime)){
                return [
                    'success' => false,
                    'message' =>"منذورة سلبينا الوقت قطع الفترة يعني قحط من هووون شبنا قياساتي؟؟ ",
                    'data' => []
                ];
            }


            $app = AppointmentHomeCare::where('patient_id', $user->patient->id)
                ->whereHas('appointment_home_session_nurse', function ($q) use ($period, $request) {
                    $q->where('time_in', $period->date)
                        ->whereHas('nurse_session.work_employee_Day', function ($q) use ($request) {
                            $q->where('history', $request->date);
                        });
                })->first();
            if($app){
               return [
                   'success'=>false,
                   'message'=>__('messages.appointment_time'),
                   'data'=>[]
               ];
            }


            $busy=false;
            $nurse_choice=User::class;
            foreach ($nurses as $nurse){
                $period_nurse =$nurse->work_employees->first()?->nurse_sessions;
                $session_need=$period_nurse->where('time_in',$period->date)->first();
                if($session_need->status == SessionNurseStatus::Available){

                    $appointment=AppointmentHomeCare::create([
                        'patient_id'=>$user->patient->id,
                        'nurse_session_id'=>$session_need->id,
                        'type'=>$request->service_type,
                        'gender'=>$request->gender,
                        'location'=>$request->location,
                        'phone_number'=>$request->phone,
                        'notes'=>$request->notes,
                    ]);

                    $session_need->update(['status'=>SessionNurseStatus::UnAvailable]);
                    $nurse->update([
                        'num_patients'=>$nurse->num_patients+1,
                    ]);

                    $assigned=Assigned::where([
                        'assignedable_id' => $nurse->id,
                        'assignedable_type' => User::class,
                        'patient_id' => $user->patient->id,
                    ])->first();
                    if(!$assigned){
                       $nurse->assigned()->create([
                           'patient_id'=>$user->patient->id,
                           'active'=>true
                       ]);
                    }

                    $point=Point::where('name_en','Request home service online')->first();
                    $userPoint=UserPoint::create([
                        'patient_id'=>$user->patient->id,
                        'point_id'=>$point->id ,
                        'pointable_type'=>AppointmentHomeCare::class,
                        'pointable_id'=>$appointment->id,
                        'history'=>Carbon::today(),
                        'point_number'=>$point->point_number,
                    ]);
                    $user->patient->update(["totalPoints"=>$user->patient->totalPoints + $userPoint->point_number]);
//                    $nurse_choice=$nurse->select('id','first_name','last_name','gender','phone')->first();
                    $nurse_choice=$nurse;
                    $busy=true;
                    break;
                }


            }

            if(!$busy){
                return [
                    'success'=>false,
                    'message'=>__('messages.appointment_status'),
                    'data'=>[]
                ];
            }
            return [
                'success'=>true,
                'message'=>__('messages.reserve_success'),
                'data'=>[
                    'appointment_info'=>$appointment,
                    'nurse_info'=>$nurse_choice->makeHidden(['work_employees']),
                    ],

            ];



        });

        return $registered;


    }







}
