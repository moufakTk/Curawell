<?php

namespace App\Services\Appointment;


use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Appointments\appointment\AppointmentType;
use App\Enums\Payments\BallStatus;
use App\Enums\Sessions\SessionCenterType;
use App\Enums\Sessions\SessionDoctorStatus;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Events\WhatsAppTaxi;
use App\Models\Appointment;
use App\Models\AppointmentBill;
use App\Models\Assigned;
use App\Models\Bill;
use App\Models\Competence;
use App\Models\Discount;
use App\Models\Doctor;
use App\Models\Doctor_examin;
use App\Models\DoctorSession;
use App\Models\OrderTaxi;
use App\Models\Patient;
use App\Models\Point;
use App\Models\Replacement;
use App\Models\Service;
use App\Models\SessionCenter;
use App\Models\User;
use App\Models\UserDayTime;
use App\Models\UserPoint;
use App\Models\UserReplacement;
use App\Models\WorkDay;
use App\Models\WorkEmployee;
use App\Models\WorkLocation;
use App\Services\Dashpords\ForAllService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentService
{

    protected $forAllService;

    protected $locale;

    public function __construct(ForAllService $forAllService)
    {
        $this->locale = app()->getLocale();
        $this->forAllService = $forAllService;
    }

    public function getDoctorServices($request)
    {

        $service_id = $request->service_id;
        $competence = Competence::where('service_id', $service_id)
            ->with(['work_locations' => function ($query) {
                $query->where('active', 1);
            }], 'work_locations.work_location_user.doctor.doctor_user')->get();

        return $competence;

    }

    public function competences($request)
    {
        $service_id = $request->service_id;
        $competences = Competence::where('service_id', $service_id)->get();
        return $competences;

    }

    public function competenceDoctors($request)
    {
        if($request->has('competence_id')){
            $users=User::where('user_type',UserType::Doctor)
                ->whereHas('doctor' ,function($query) use($request){
                    $query->where('doctor_type',DoctorType::Clinic);
                })->whereHas('work_location',function($query) use($request){
                    $query->where([
                        'locationable_type'=>Competence::class,
                        'locationable_id'=>$request->competence_id,
                        'active'=>1
                    ]);
                })->with(['doctor','work_day_time' ,'work_employees' => function ($q) use($request){
                    $q->where('work_day_id' ,WorkDay::where('history',$request->date)->value('id'));
                }])->get()->each(function($user) use($request){
                    $c=Competence::where('id',$request->competence_id)->whereHas('work_locations',function($query) use($request,$user){
                        $query->where(['user_id'=>$user->id,'locationable_type'=>Competence::class,'active'=>1]);
                    })->value('name_'.$this->locale);
                    $user->competence_name =$c ;
                });

            return $users;

        }

        $users=User::where('user_type',UserType::Doctor)
            ->whereHas('doctor' ,function($query) use($request){
                $query->where('doctor_type',DoctorType::Clinic);
            })->whereHas('work_location',function($query) use($request){
                $query->where([
                    'locationable_type'=>Competence::class,
                    //'locationable_id'=>$request->competence_id,
                    'active'=>1
                ])->whereHasMorph('locationable', [Competence::class], function($q) use($request){
                    $q->where('service_id', $request->service_id);
                });
            })->with(['doctor','work_day_time' ,'work_employees' => function ($q) use($request){
                $q->where('work_day_id' ,WorkDay::where('history',$request->date)->value('id'));
            }])->get()->each(function ($user) use($request){
                $c=Competence::whereHas('work_locations',function($query) use($request,$user){
                    $query->where(['user_id'=>$user->id,'locationable_type'=>Competence::class,'active'=>1]);
                })->value('name_'.$this->locale);
                $user->competence_name =$c ;
            });

        return $users;

    }

    public function doctorStatus($request)
    {
        $work_day_id = WorkDay::where('history', $request->date)->value('id');

        $doctor=Doctor::where('id',$request->doctor_id)->with(['doctor_user.work_employees'=>function ($query) use($request ,$work_day_id){
                $query->where('work_day_id',$work_day_id);
            }])->get();
       // $udw=$doctor->doctor_user->work_employees;
        return $doctor;
    }                  //ما محتاجينو ممبدأيا دمجتو مع التابع competenceDoctors

    public function serviceOffers($request)
    {
        $discounts = Discount::where(['active' => 1, 'service_id' => $request->service_id])
            ->select('id',
                'name_' . $this->locale,
                'description_' . $this->locale,
                'discount_rate', 'active')
            ->get();

        return $discounts;

    }

    public function dayAndSession($request)
    {

        $doctor = Doctor::where('id', $request->doctor_id)->with('doctor_user')->first();

        if (!$doctor) {
            return ['success' => false, 'message' => __('messages.doctor_not_exist')];
        }

        $day = WorkDay::where('history', $request->day_date)->first();
        if (in_array($day->day_en, ['Friday'])) {
            return ['success' => false, 'message' => __('messages.name_day')];
        }
        $work_day_of_doctor = WorkEmployee::where(['user_id' => $doctor->doctor_user->id, 'work_day_id' => $day->id])->with('doctor_sessions')->get();


        return ['success' => true,
            'message' => __('messages.success_return'),
            'data' => $work_day_of_doctor,];
    }

    public function reserveAppointment($request)
    {
        $registered = DB::transaction(function () use ($request) {


            if($request->mode=='FaceToFace'){
                $patient=Patient::where('patient_num' ,$request->number_patient)->first();
                $user = $patient->patient_user;
            }else{
                $user=User::where('id',auth()->user()->id)->with('patient')->first();
            }

            $doctor=Doctor::where('id', $request->doctor_id)->with('doctor_user','doctor_examination')->first();
            $department=$doctor->doctor_user->active_work_location->locationable->competence_services->name_en;
            $doctor_session = DoctorSession::where('id', $request->doctor_session_id)->with('session_doctor.work_employee_Day')->first();
            $rep=Replacement::where('name_en',$department)->first();

            if($request->mode ==="Point"){
                if($user->patient->totalPoints < $rep->replace_point_num){
                    return [
                        'success' => false,
                        'message' => 'منذورة ما معك نقاط تكفي درمل من هون',
                        'data' => []
                    ];}

            }

            if($this->forAllService->check_doctor_session($doctor_session ,$doctor)){
                return ['success' => false,
                    'message' => __('messages.doctor_periods'),
                    'data'=>[],
                ];
            }

            if ($this->forAllService->test_doctor_session($doctor_session)) {
                return ['success' => false,
                    'message' => __('messages.session_not_available'),
                    'data' => [],
                ];
            }

            $date =$doctor_session->session_doctor->work_employee_Day->history;
            $time =$doctor_session->from;

            $sessionDateTime = Carbon::parse("$date $time");



            if(now()->greaterThanOrEqualTo($sessionDateTime)){
                return [
                    'success' => false,
                    'message' =>"حمودة سلبينا الوقت قطع الفترة يعني هوينا ",
                    'data' => []
                ];
            }


            $appointment = Appointment::create([
                'patient_id' =>$user->patient->id,
                'doctor_id' => $request->doctor_id,
                'doctor_session_id' => $request->doctor_session_id,
                'phone_number' => $request->phone,
                'status' => AppointmentStatus::Confirmed,
                'delivery' => $request->taxi_order,
                'delivery_location_en' =>($request->taxi_order == true)?$request->location_order :null,
                'appointment_type' => $request->mode,
            ]);


            DoctorSession::where('id', $request->doctor_session_id)->update(['status' => SessionDoctorStatus::Reserved]);


            //$this->forAllService->check_given_point($appointment);


            if($request->taxi_order == true) {
                $fullDateTime = Carbon::parse(
                    Carbon::parse($doctor_session->session_doctor->work_employee_Day->history)->format('Y-m-d') . ' ' . $doctor_session->from
                );

                $order = OrderTaxi::create([
                    'patient_id' => $user->patient->id,
                    'phone' => $request->phone,
                    'address' => $request->location_order,
                    'date' => $fullDateTime,
                ]);
                event(new WhatsAppTaxi($user, $order));
            }


            $assigned=Assigned::where([
                'assignedable_id' => $doctor->id,
                'assignedable_type' => Doctor::class,
                'patient_id' => $user->patient->id,
            ])->first();
            if(!$assigned){
                $doctor->assigned()->create([
                    'patient_id' => $user->patient->id,
                    'active' => true,
                ]);

                $doctor->doctor_user->update([
                    'num_patients'=>$doctor->doctor_user->num_patients+1,
                ]);

            }else{
                if($assigned->active==false){
                    $doctor->assigned()->update(['active' => true]);
                }
            }



            $session =SessionCenter::create([
                'sessionable_type'=>Appointment::class,
                'sessionable_id'=>$appointment->id,
                'session_type'=>SessionCenterType::Clinic,
                'doctor_examination'=>$doctor->doctor_examination->price,
                'doctor_examination_discount'=>$doctor->doctor_examination->discount_rate,

            ]);

            if($request->mode ==='Electronically' ||$request->mode ==='FaceToFace'){
                $bill=Bill::where([ 'doctor_id'=>$doctor->id,
                    'patient_id'=>$user->patient->id,
                    'status'=>BallStatus::Incomplete
                ])->first();
                if($bill){
                    $appointment->appointment_bills()->create(['bill_id'=>$bill->id]);
                }else{
                    $bill=Bill::create([
                        'doctor_id'=>$doctor->id,
                        'patient_id'=>$user->patient->id,
                        'status'=>BallStatus::Incomplete,
                    ]);
                    $bill->update(['private_num'=>'#001-'.$bill->id,]);
                    $appointment->appointment_bills()->create(['bill_id'=>$bill->id]);
                }
            }elseif ($request->mode ==='Point') {


                $ure=UserReplacement::create([
                    'patient_id'=>$user->patient->id,
                    'replacement_id'=>$rep->id,
                    'appointment_id'=>$appointment->id,
                    "replace_point_num"=>$rep->replace_point_num,
                ]);

                $user->patient->update(["totalPoints"=>$user->patient->totalPoints-$ure->replace_point_num]);

                $bill =Bill::create([
                    'doctor_id'=>$doctor->id,
                    'patient_id'=>$user->patient->id,
                    'status'=>BallStatus::Incomplete,
                ]);
                $bill->update(['private_num'=>'#001-'.$bill->id,]);
                $appointment->appointment_bills()->create(['bill_id'=>$bill->id]);
            }





            return ['success'=>true,
                'message'=>__('messages.success_reserve'),
                'data'=>$appointment,
            ];


        });

        return $registered;

    }





}



