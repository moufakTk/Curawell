<?php

namespace App\Services\Dashpords;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Appointments\Waiting\WaitingStatus;
use App\Enums\Payments\BallStatus;
use App\Enums\Sessions\SessionCenterType;
use App\Enums\Sessions\SessionDoctorStatus;
use App\Enums\WorkStatus\PeriodStatus;
use App\Events\DeleteOrderTaxi;
use App\Events\SendSorryMessage;
use App\Events\UpdateTimeTaxiOrder;
use App\Http\Resources\AppointmentDoctorResource;
use App\Http\Resources\AppointmentOccurResource;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\BillResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\WaitingResource;
use App\Models\Appointment;
use App\Models\AppointmentBill;
use App\Models\Assigned;
use App\Models\Bill;
use App\Models\Competence;
use App\Models\Doctor;
use App\Models\DoctorSession;
use App\Models\OrderTaxi;
use App\Models\Patient;
use App\Models\SessionCenter;
use App\Models\User;
use App\Models\UserDayTime;
use App\Models\Waiting;
use App\Models\WorkDay;
use App\Models\WorkEmployee;
use App\Models\WorkLocation;
use App\Services\AuthServices\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class DashpordSecretaryService
{

    protected $forAllService,$dashpordDoctorService ,$locale,$authService;


    public function __construct(ForAllService $forAllService ,DashpordDoctorService $dashpordDoctorService,AuthServices $authService)
    {
        $this->forAllService = $forAllService;
        $this->locale=app()->getLocale();
        $this->dashpordDoctorService=$dashpordDoctorService;
        $this->authService=$authService;
    }

    public function reserve_appointment_waiting($request)
    {

        $registered = DB::transaction(function () use ($request) {
            $patient= Patient::where(['patient_num'=>$request->number_patient])->first();
            $doctor=Doctor::where('id', $request->doctor_id)->with('doctor_user','doctor_examination')->first();

            $waiting=Waiting::create([
                'patient_id'=>$patient->id,
                'doctor_id'=>$doctor->id,
                'phone_number'=>$request->phone,
                'waiting_type'=>$request->type_waiting,
            ]);


            $session =SessionCenter::create([
                'sessionable_type'=>Waiting::class,
                'sessionable_id'=>$waiting->id,
                'session_type'=>SessionCenterType::Clinic,
                'doctor_examination'=>$doctor->doctor_examination->price,
                'doctor_examination_discount'=>$doctor->doctor_examination->discount_rate,

            ]);


            $assigned=Assigned::where([
                'assignedable_id' => $doctor->id,
                'assignedable_type' => Doctor::class,
                'patient_id' => $patient->id,
            ])->first();
            if(!$assigned){
                $doctor->assigned()->create([
                    'patient_id' => $patient->id,
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


            $bill=Bill::where([ 'doctor_id'=>$doctor->id,
                'patient_id'=>$patient->id,
                'status'=>BallStatus::Incomplete
            ])->first();
            if($bill){
                $waiting->appointment_bills()->create(['bill_id'=>$bill->id]);
            }else{
                $bill=Bill::create([
                    'doctor_id'=>$doctor->id,
                    'patient_id'=>$patient->id,
                    'status'=>BallStatus::Incomplete,
                ]);
                $bill->update(['private_num'=>'#001-'.$bill->id,]);
                $waiting->appointment_bills()->create(['bill_id'=>$bill->id]);
            }

            return $waiting;

    });

     return $registered;
    }

    public function update_appointment($request){

        $registered = DB::transaction(function () use ($request) {



            $appointment =Appointment::where('id',$request->appointment_id)->first();
            $session_old=$appointment->appointment_doctor_session;

            $doctor_session = DoctorSession::where('id', $request->new_session_id)->with('session_doctor.work_employee_Day')->first();


            if($this->forAllService->check_doctor_session($doctor_session ,$appointment->appointment_doctor)){
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

            $appointment->update([
                'doctor_session_id'=>$request->new_session_id
            ]);
            $session_old->update([
                'status'=>SessionDoctorStatus::Available
            ]);
            $doctor_session->update([
                'status'=>SessionDoctorStatus::Reserved
            ]);

            if($appointment->delivery){
                $this->message_update_order_taxi($appointment->appointment_patient ,$session_old ,$doctor_session);
            }

            return $appointment ;

        });

        return $registered;
    }
    public function message_update_order_taxi($patient,$session_old,$doctor_session)
    {
        //$from_time = trim($doctor_session->from);
        $fullDateTime = Carbon::parse(
            Carbon::parse($doctor_session->session_doctor->work_employee_Day->history)->format('Y-m-d') . ' ' . $doctor_session->from
        );
        //$from_time = trim($session_old->from);
        $fullDateTime_old = Carbon::parse(
            Carbon::parse($session_old->session_doctor->work_employee_Day->history)->format('Y-m-d') . ' ' . $session_old->from
        );

        $order_taxi = OrderTaxi::where('patient_id', $patient->id)
            ->whereDate('date', $fullDateTime_old->format('Y-m-d'))
            ->whereTime('date', $fullDateTime_old->format('H:i'))
            ->first();
        $orderTaxi_new=$order_taxi->update([
            'date'=>$fullDateTime
        ]);
        event(new UpdateTimeTaxiOrder($patient->patient_user ,$fullDateTime, $fullDateTime_old));

        return $order_taxi;

    }

    public function delete_appointment($request)
    {

        $registered = DB::transaction(function () use ($request) {
            $appointment= Appointment::where('id',$request->appointment_id)->first();
            $doctor_session=$appointment->appointment_doctor_session;
            $doctor_user=$appointment->appointment_doctor->doctor_user;
            $patient=$appointment->appointment_patient;
            $doctor =$appointment->appointment_doctor;
            $appointment_bill =$appointment->appointment_bills()->first();

            if(!$this->test_have_appointment($patient ,$doctor,$appointment))
            {
                $doctor_user->update([
                    'num_patients'=>$doctor_user->num_patients-1,
                ]);
                Assigned::where(["assignedable_type"=>Doctor::class,'assignedable_id'=>$doctor->id,'patient_id'=>$patient->id])->delete();

            }else{
                if(!$this->test_relationship($doctor_user ,$patient ,$appointment)){
                    Assigned::where(["assignedable_type"=>Doctor::class,'assignedable_id'=>$doctor->id,'patient_id'=>$patient->id])->update(['active'=>false]);
                }
            }

            if($this->check_bill_appointment($appointment)){
                AppointmentBill::where(['appointable_type'=>Appointment::class,'appointable_id'=>$appointment->id])->delete();
            }else{
                Bill::where('id',$appointment_bill->bill_id)->delete();
            }


            if($request->styl_canceled ==='FromPatient'){
                $this->send_message_delete_taxi($appointment);
            }elseif ($request->styl_canceled ==='FromDoctor'){
                $this->forAllService->check_given_point($appointment);
                event(new SendSorryMessage($patient->patient_user,$appointment->phone_number));
                $this->send_message_delete_taxi($appointment);

            }


            $appointment->update([
                "status"=>AppointmentStatus::Cancel
            ]);

            $doctor_session->update([
                'status'=>SessionDoctorStatus::Available,
            ]);

            return $appointment;

        });

        return $registered;
    }
    public function test_have_appointment($patient ,$doctor ,$appointment)
    {
        $bool=false;
        $appointments=Appointment::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])->where('id','!=',$appointment->id)->get();
        $waitings =Waiting::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])->get();

        if($appointments->isNotEmpty()){
            foreach ($appointments as $appointment) {
                if($appointment->status !== AppointmentStatus::Cancel ){
                    return true;
                }
            }
        }

        if($waitings->isNotEmpty()){
            foreach ($waitings as $waiting) {
                if($waiting->status !== WaitingStatus::Cancel){
                    return true;
                }
            }
        }
        return $bool;
    }
    public function test_relationship($patient,$doctor,$appointment)
    {
        $bool=false;

        $appointment=Appointment::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])
            ->where('id','!=',$appointment->id)
            ->whereIn('status',[AppointmentStatus::Confirmed,AppointmentStatus::Occur])
            ->first();

        $waiting =Waiting::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])
            ->whereIn('status',[WaitingStatus::Confirmed,WaitingStatus::Occur])
            ->first();

        if($appointment){
            return true;
        }

        if($waiting){
            return true;
        }

        return $bool;

    }

    public function delete_waiting($request)
    {

        $registered = DB::transaction(function () use ($request) {
            $waiting =Waiting::where('id',$request->waiting_id)->first();
            $patient=$waiting->waiting_patient;
            $doctor =$waiting->waiting_doctor;
            $doctor_user=$waiting->waiting_doctor->doctor_user;
            $appointment_bill=$waiting->appointment_bills()->first();

            if(!$this->test_have_waiting($patient ,$doctor,$waiting))
            {
                $doctor_user->update([
                    'num_patients'=>$doctor_user->num_patients-1,
                ]);
                Assigned::where(["assignedable_type"=>Doctor::class,'assignedable_id'=>$doctor->id,'patient_id'=>$patient->id])->delete();

            }else{
                if(!$this->test_relationship_waiting($patient ,$doctor ,$waiting)){
                    Assigned::where(["assignedable_type"=>Doctor::class,'assignedable_id'=>$doctor->id,'patient_id'=>$patient->id])->update(['active'=>false]);
                }
            }


            if($this->check_bill_appointment($waiting)){
                AppointmentBill::where(['appointable_type'=>Waiting::class,'appointable_id'=>$waiting->id])->delete();
            }else{
                Bill::where('id',$appointment_bill->bill_id)->delete();
            }


            $waiting->update([
                'status'=>WaitingStatus::Cancel,
            ]);

            return $waiting ;
        });

        return $registered;
    }
    public function test_have_waiting($patient ,$doctor ,$waiting)
    {
        $bool=false;
        $appointments=Appointment::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])->get();
        $waitings =Waiting::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])->where('id','!=',$waiting->id)->get();

        if($appointments->isNotEmpty()){
            foreach ($appointments as $appointment) {
                if($appointment->status !== AppointmentStatus::Cancel ){
                    return true;
                }
            }
        }

        if($waitings->isNotEmpty()){
            foreach ($waitings as $waiting) {
                if($waiting->status !== WaitingStatus::Cancel){
                    return true;
                }
            }
        }
        return $bool;


    }
    public function test_relationship_waiting($patient,$doctor,$waiting)
    {
        $bool=false;

        $appointment=Appointment::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])
            ->whereIn('status',[AppointmentStatus::Confirmed,AppointmentStatus::Occur])
            ->first();

        $waiting =Waiting::where(['patient_id'=> $patient->id ,'doctor_id'=> $doctor->id])
            ->whereIn('status',[WaitingStatus::Confirmed,WaitingStatus::Occur])
            ->where('id','!=',$waiting->id)
            ->first();

        if($appointment){
            return true;
        }

        if($waiting){
            return true;
        }

        return $bool;

    }
    public function check_bill_appointment($appointment)
    {
        $bool=false;

        $appointment_bill =$appointment->appointment_bills()->first();

        $a_bs=AppointmentBill::where('bill_id',$appointment_bill->bill_id)->get();
        $count=$a_bs->count();
        if($count>1){
            $bool=true;
        }
        return $bool;
    }

    public function send_message_delete_taxi($appointment)
    {
        $registered = DB::transaction(function () use ($appointment) {

            $doctor_session=$appointment->appointment_doctor_session;
            $patient=$appointment->appointment_patient;

            $fullDateTime = Carbon::parse(
                Carbon::parse($doctor_session->session_doctor->work_employee_Day->history)->format('Y-m-d') . ' ' . $doctor_session->from
            );
            $order_taxi = OrderTaxi::where('patient_id', $patient->id)
                ->whereDate('date', $fullDateTime->format('Y-m-d'))
                ->whereTime('date', $fullDateTime->format('H:i'))
                ->first();

            if($order_taxi){
                $order_taxi->update([
                    'status'=>0,
                ]);

                $appointment->update(['delivery'=>false]);

                event(new DeleteOrderTaxi($patient->patient_user ,$fullDateTime));
            }


            return $order_taxi ;

        });
        return $registered;

    }
    public function Forbidden_day_doctor($request)
    {

        $registered = DB::transaction(function () use ($request) {

            $work_day=WorkDay::where('history',$request->date)->first();
            $doctor=Doctor::where('id',$request->doctor_id)->first();
            $user= $doctor->doctor_user;
            $work_employee=WorkEmployee::where(['work_day_id'=>$work_day->id ,'user_id'=>$user->id])->first();

            $work_employee->update([
                'status'=>PeriodStatus::FORBIDDEN
            ]);

            return $work_employee;

        });
        return $registered;
    }
    public function secretary_queue()
    {

        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();

        $app=[];
        foreach ($user_doctor as $user) {
            $u=User::where('id',$user)->first();

            $app[]= [
                'doctor_name'=>$u->getFullNameAttribute(),
                'doctor_id'=>$u->doctor->id,
                'appointment'=>AppointmentResource::collection($this->appointment_doctor_confirmed($user,$time_now)),
                'waiting'=> WaitingResource::collection($this->waiting_doctor($user,$time_now)),
                'appointment_missed'=>AppointmentResource::collection($this->appointment_doctor_missed($user,$time_now)),
            ];
        }

        return $app;



    }
    public function secretary_queue_appointment_doctor()
    {
        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();
        $app=[];
        $wai=[];
        foreach ($user_doctor as $user)
        {
            $appointment=$this->appointment_doctor_occur($user,$time_now);
            $waiting = $this->waiting_doctor_occur($user,$time_now);
            if($appointment->isNotEmpty()){
                $app[]=$appointment;
            }
            if($waiting->isNotEmpty()){
                $wai[]=$waiting;
            }


        }

        return array_merge($app, $wai);

    }
    public function secretary_queue_checkOut()
    {
        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();

        $app=[];
        $wai=[];
        foreach ($user_doctor as $user)
        {
            $appointment=$this->appointment_doctor_checkout($user,$time_now);
            $waiting = $this->waiting_doctor_checkOut($user,$time_now);
            if($appointment->isNotEmpty()){
                $app[]=$appointment;
            }
            if($waiting->isNotEmpty()){
                $wai[]=$waiting;
            }


        }

        return array_merge($app, $wai);

    }
    public function appointment_doctor_occur($user,$time_now)
    {

        $us =User::where('id',$user)->first();
        $competence_id=$us->active_work_location->locationable_id;
        $competence_name =Competence::where('id',$competence_id)->first();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>$user])->first();
        $appointment_occur = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Occur);
        })->with('appointments.appointment_patient','appointments.appointment_doctor')->get()->each(function ($ap) use ($competence_name){
            $ap->kind ='Appointment';
            $ap->department_doctor = $competence_name->{'name_'.$this->locale};

        });

        return $appointment_occur->map(function ($appointment) {
            return new AppointmentOccurResource($appointment, 'Appointment');
        });
    }
    public function appointment_doctor_missed($user,$time_now){

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>$user])->first();
        $appointment_missed = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Missed);
        })->with('appointments.appointment_patient.patient_user')->get();
        return $appointment_missed;
    }
    public function appointment_doctor_confirmed($user,$time_now)
    {

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>$user])->first();
        $appointment_confirmed = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Confirmed);
        })->with('appointments.appointment_patient.patient_user')->get()->each(function ($q){
            $q->Kind='Appointment';
        });

        return $appointment_confirmed;

    }
    public function appointment_doctor_checkout($user,$time_now)
    {
        $us =User::where('id',$user)->first();
        $competence_id=$us->active_work_location->locationable_id;
        $competence_name =Competence::where('id',$competence_id)->first();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>$user])->first();
        $appointment_checkOut = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::CheckOut);
        })->with('appointments.appointment_patient','appointments.appointment_doctor',"appointments.appointment_bills")->get()->each(function ($ap) use ($competence_name){
            $ap->kind ='Appointment';
            $ap->department_doctor = $competence_name->{'name_'.$this->locale};

        });

        return $appointment_checkOut->map(function ($appointment) {
            return new AppointmentOccurResource($appointment, 'Appointment_checkOut');
        });
           // return $appointment_checkOut;

    }

    public function waiting_doctor($user ,$time_now)
    {
        $doctor=Doctor::where('user_id',$user)->value('id');

        $waiting= Waiting::where(['doctor_id'=>$doctor  ,'status'=>WaitingStatus::Confirmed])->whereDate('created_at',$time_now)->with('waiting_patient','sesstions')->get();

        return $waiting;
    }
    public function waiting_doctor_occur($user,$time_now)
    {
        $us =User::where('id',$user)->first();
        $competence_id=$us->active_work_location->locationable_id;
        $competence_name =Competence::where('id',$competence_id)->first();

        $waiting= Waiting::where(['doctor_id'=>$user ,'status'=>WaitingStatus::Occur])->whereDate('created_at',$time_now)->with('waiting_patient','waiting_doctor','sesstions')->get()->each(function ($q) use ($competence_name){
            $q->kind='Waiting';
            $q->department_doctor=$competence_name->{'name_'.$this->locale};
        });
        return $waiting->map(function ($appointment) {
            return new AppointmentOccurResource($appointment, 'Waiting');
        });
    }
    public function waiting_doctor_checkOut($user,$time_now)
    {
        $us =User::where('id',$user)->first();
        $competence_id=$us->active_work_location->locationable_id;
        $competence_name =Competence::where('id',$competence_id)->first();

        $waiting= Waiting::where(['doctor_id'=>$user ,'status'=>WaitingStatus::CheckOut])->whereDate('created_at',$time_now)->with('waiting_patient','waiting_doctor','sesstions','appointment_bills')->get()->each(function ($q) use ($competence_name){
            $q->kind='Waiting';
            $q->department_doctor=$competence_name->{'name_'.$this->locale};
        });
        return $waiting->map(function ($appointment) {
            return new AppointmentOccurResource($appointment, 'Waiting_checkOut');
        });
    }

    public function make_appointment_occur($request)
    {

        if($request->kind=='Waiting'){
            $appointment=Waiting::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>WaitingStatus::Occur]);
        }elseif ($request->kind =="Appointment"){
            $appointment =Appointment::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>AppointmentStatus::Occur]);
        }
        return $appointment;

    }
    public function make_appointment_checkout($request)
    {
        if($request->kind=='Waiting'){
            $appointment=Waiting::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>WaitingStatus::CheckOut]);
        }elseif ($request->kind =="Appointment"){
            $appointment =Appointment::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>AppointmentStatus::CheckOut]);
        }
        return $appointment;

    }
    public function make_appointment_don($request)
    {
        if($request->kind=='Waiting'){
            $appointment=Waiting::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>WaitingStatus::Don]);
        }elseif ($request->kind =="Appointment"){
            $appointment =Appointment::where('id',$request->appointment_id)->first();
            $appointment->update(['status'=>AppointmentStatus::Don]);
            $this->forAllService->check_given_point($appointment);
        }

        return $appointment ;

    }


    public function bill_for_appointment($request)
    {

        if($request->kind =='Waiting'){
            $waiting =Waiting::where('id',$request->appointment_id)->with('sesstions')->first();
            $appointment_bill=$waiting->appointment_bills()->first();
            $bill=Bill::where(['id'=>$appointment_bill->bill_id])->with('doctorEdits','appointment_bills')->first();
            $bill->session_id=$waiting->sesstions->first()->id;
        }elseif ($request->kind =="Appointment"){
            $appointment=Appointment::where('id',$request->appointment_id)->with('sesstions')->first();
            $appointment_bill=$appointment->appointment_bills()->first();
            $bill=Bill::where(['id'=>$appointment_bill->bill_id])->with('doctorEdits','appointment_bills')->first();
            $bill->session_id=$appointment->sesstions->first()->id;
        }
        return new BillResource($bill ,'Secretary');
        //return $bill;
    }
    public function update_paid_of_appointment($request)
    {


        if($request->kind == 'Waiting'){
            $waiting =Waiting::where('id',$request->appointment_id)->with('sesstions')->first();
            $a_b=$waiting->appointment_bills()->first();
            $bill=Bill::where(['id'=>$a_b->bill_id])->first();
            $amount=$a_b->paid_of_amount;
            $a_b->update([
                'paid_of_amount'=>$request->edit_amount
            ]);

            $bill->update([
                'paid_of_bill'=>$bill->paid_of_bill+$a_b->paid_of_amount-$amount
            ]);


        }elseif($request->kind == 'Appointment'){
            $appointment =Appointment::where('id',$request->appointment_id)->with('sesstions')->first();
            $a_b=$appointment->appointment_bills()->first();
            $bill=Bill::where(['id'=>$a_b->bill_id])->first();
            $amount=$a_b->paid_of_amount;
            $a_b->update([
                'paid_of_amount'=>$request->edit_amount
            ]);

            $bill->update([
                'paid_of_bill'=>$bill->paid_of_bill+$a_b->paid_of_amount-$amount
            ]);
        }

        return [
            'bill'=>$bill,
            'Appointment_bill'=>$a_b
        ];
    }

    public function update_paid_of_bill($request)
    {
        $bill=Bill::where(['id'=>$request->bill_id])->first();

        $bill->update([
            'paid_of_bill'=>$request->amount_paid
        ]);

        return [
            'success'=>true,
            'message'=>'تم التعديل',
            'data'=>$bill
        ];
    }
    public function update_status_bill($request)
    {
        $bill=Bill::where(['id'=>$request->bill_id])->first();

        $bill->update([
            'status'=>BallStatus::Complete
        ]);

        return [
            'success'=>true,
            'message'=>'تم تعديل حالة الفاتورة لمكتملة',
            'data'=>$bill
        ];
    }

    public function update_appointment_to_missed($appointment)
    {

        $appointment->update(['status'=>AppointmentStatus::Missed]);
        return $appointment;
    }

    public function secretary_patients()
    {
        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();

        $doctors =Doctor::whereIn('user_id',$user_doctor)->get();
        $patients = collect();
        $number_patients=0;
        foreach ($doctors as $doctor){
            $re =$this->dashpordDoctorService->doctor_patients($doctor);
            $number_patients+=$re['num_patients'];
            if($re['patients']->isNotEmpty()){
                $patients = $patients->merge($re['patients']);
            }

        }

        return [
            'num_patients'=>$number_patients,
            'patients'=> $patients->map(fn($p) => new PatientResource($p, 'secretary'))
        ];

    }

    public function all_appointment_secretary()
    {
        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();
        $sum_all_app_res=0;
        $sum_all_app_don=0;
        $app = collect();
        foreach($user_doctor as $user){
            $re_num=$this->number_appointment($user);
            $sum_all_app_res+=$re_num['appointment_reserved'];
            $sum_all_app_don+=$re_num['appointment_done'];
            $appointment =$this->all_appointments_doctor($user);
            if($appointment->isNotEmpty()){
                $app=$app->merge($appointment);
                //$app[]=$appointment;
            }

        }

        return [
            'appointment_reserved'=>$sum_all_app_res,
            'appointment_done'=>$sum_all_app_don,
            'appointments'=>$app
        ];
    }

    public function all_appointments_doctor($user)
    {
        $doctor =Doctor::where('user_id',$user)->first();

        $appointment =Appointment::where('doctor_id',$doctor->id)->with('sesstions','appointment_doctor','appointment_patient')->get()->each(function ($appointment) {
            $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
            $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
            $appointment->department=$competence_name;

            $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
            $appointment->time =$appointment->appointment_doctor_session->from;
            //$appointment->type_serv ='Clinic';
            $appointment->bill=$appointment->appointment_bills()->first()->total_treatment_amount;
            $appointment->paid_bill=$appointment->appointment_bills()->first()->paid_of_amount;
        });
        return $appointment->map(function ($item) {
            return new AppointmentDoctorResource($item, 'Secretary');
        });

    }
    public function number_appointment($user)
    {
        $doctor=Doctor::where('user_id',$user)->first();
        $num_app_res= Appointment::where(['doctor_id'=>$doctor->id,'status'=>AppointmentStatus::Confirmed])->count();
        $num_app_don=Appointment::where(['doctor_id'=>$doctor->id,'status'=>AppointmentStatus::Don])->count();
        return [
            'appointment_reserved'=>$num_app_res,
            'appointment_done'=>$num_app_don,
        ];
    }
    public function appointment_secretary_patient($patent)
    {

        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();

        $app=  collect();
        foreach ($user_doctor as $user){
            $appointment=$this->appointment_doctor_patient($patent,$user);
            if($appointment->isNotEmpty()){
                $app=$app->merge($appointment);
            }
        }

        return $app;

    }

    public function appointment_doctor_patient($patient ,$user)
    {
        $doctor=Doctor::where('user_id',$user)->first();

        $appointment =Appointment::where(['doctor_id'=>$doctor->id,'patient_id'=>$patient->id])->with('sesstions','appointment_doctor','appointment_patient')->get()->each(function ($appointment) {
            $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
            $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
            $appointment->department=$competence_name;

            $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
            $appointment->time =$appointment->appointment_doctor_session->from;
            //$appointment->type_serv ='Clinic';
            $appointment->bill=$appointment->appointment_bills()->first()->total_treatment_amount;
            $appointment->paid_bill=$appointment->appointment_bills()->first()->paid_of_amount;
        });


        return $appointment->map(function ($item) {
            return new AppointmentDoctorResource($item, 'Secretary');
        });

    }

    public function bill_patient_secretary($patient)
    {

        $bill=Bill::where('patient_id', $patient->id)
            ->with(['doctorEdits', 'appointment_bills' => function($q) {
                $q->whereHasMorph('appointable', [Appointment::class], function($query){
                    $query->where('status', AppointmentStatus::Don);
                })->orWhereHasMorph('appointable', [Waiting::class], function($query){
                    $query->where('status', WaitingStatus::Don);
                });
            }],'bill_doctor')
            ->whereHas('appointment_bills', function ($q) {
                $q->whereMorphRelation('appointable', [Appointment::class], function ($query) {
                    $query->where('status', AppointmentStatus::Don);
                })->orWhereMorphRelation('appointable', [Waiting::class], function ($query) {
                    $query->where('status', WaitingStatus::Don);
                });
            })
            ->get()->each(function ($bill){

                $bill->appointment_bills->loadMorph('appointable', [
                    Appointment::class => [
                        'appointment_doctor_session.session_doctor.work_employee_Day',
                        'appointment_doctor.doctor_user.active_work_location.locationable'
                    ],
                    Waiting::class => []]);

                $bill->deppartment =$bill->bill_doctor->doctor_user->active_work_location->locationable->{'name_'.$this->locale};
                $bill->makeHidden([
                    'bill_doctor',
                    'bill_doctor.doctor_user',
                    'bill_doctor.doctor_user.active_work_location',
                    'bill_doctor.doctor_user.active_work_location.locationable',
                ]);
            });

        return $bill->map(function ($bill) {
            return new BillResource($bill ,'Patient');
        });
    }

    public function secretary_doctors_today()
    {
        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();


        $time_now = Carbon::today('Asia/Damascus')->toDateString();
        $work_day =WorkDay::where('history',$time_now)->value('id');

        $user_doctor_today=WorkEmployee::where('work_day_id',$work_day)->whereIn('user_id',$user_doctor)->pluck('user_id')->toArray();


        $w = [];

        foreach ($user_doctor_today as $userI) {
            $work_day_today = $this->workDay_today($userI);
            $u=User::where('id',$userI)->first();
            $department=$u->active_work_location->locationable->{'name_'.$this->locale};
            $photo =$u->image->url??null;
            $w[] = [
                'work_day_today' => $work_day_today,
                'department' => $department,
                'doctor_photo'=>$photo,
                'doctor_name'=>$u->getFullNameAttribute(),
                'appointments'=>AppointmentResource::collection($this->appointment_doctor_confirmed($userI,$time_now)),
                'numbers'=>$this->number_appointment_today($userI,$time_now),
            ];
        }


        return [
            'doctors_today'=>$w,
            'doctors_Section'=>$this->doctor_section()

        ];

    }

    public function workDay_today($user)
    {
        $time_now = Carbon::today('Asia/Damascus')->toDateString();
        $work_day =WorkDay::where('history',$time_now)->value('id');
        $re =WorkEmployee::where(['work_day_id'=>$work_day,'user_id'=>$user])->select('id','from' ,'to')->get();
        return $re;
    }


    public function number_appointment_today($user,$time_now)
    {
        //$doctor=Doctor::where('user_id',$user)->first();
        //$num_app_res= Appointment::where(['doctor_id'=>$doctor->id,'status'=>AppointmentStatus::Confirmed])->count();
        //$num_app_don=Appointment::where(['doctor_id'=>$doctor->id,'status'=>AppointmentStatus::Don])->count();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>$user])->first();
        $appointment_confirmed_count = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->count();

        $appointment_confirmed_Don = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Don);
        })->with('appointments.appointment_patient.patient_user')->count();

        $appointment_confirmed_Missed = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Missed);
        })->with('appointments.appointment_patient.patient_user')->count();

        $appointment_confirmed_Occur = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Occur);
        })->with('appointments.appointment_patient.patient_user')->count();

        $appointment_confirmed_CheckOut = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::CheckOut);
        })->with('appointments.appointment_patient.patient_user')->count();

        $appointment_confirmed_Confirmed = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Confirmed);
        })->with('appointments.appointment_patient.patient_user')->count();



        return [
            'appointment_reserved'=>$appointment_confirmed_count,
            '$appointment_Confirmed'=>$appointment_confirmed_Confirmed,
            'appointment_Done'=>$appointment_confirmed_Don,
            'appointment_confirmed_Occur'=>$appointment_confirmed_Occur,
            'appointment_confirmed_CheckOut'=>$appointment_confirmed_CheckOut,
            'appointment_confirmed_Missed'=>$appointment_confirmed_Missed,

        ];
    }

    public function doctor_section(){
        $user =User::where('id',auth()->id())->first();
        $service_id=$user->active_work_location->locationable_id;
        $competence =Competence::where('service_id',$service_id)->pluck('id')->toArray();
        $user_doctor =WorkLocation::where('locationable_type',Competence::class)
            ->whereIn('locationable_id',$competence)
            ->pluck('user_id')
            ->toArray();
        $doctor=[];
        foreach ($user_doctor as $userI) {
            $u=User::where('id',$userI)->first();
            $department=$u->active_work_location->locationable->{'name_'.$this->locale};
           $this->authService->attachDefaultAvatarIfMissing($u);
            $photo=$u->image->url??null;
            $doctor[] = [
                'department' => $department,
                 'doctor_photo'=>$photo,
                'doctor_name'=>$u->getFullNameAttribute(),
                'phone'=>$u->phone,
                'work_day_today' =>UserDayTime::where('user_id',$userI)->select('id','day_'.$this->locale,'timeStart','timeEnd')->get(),
            ];
        }

    return $doctor;
    }


}
