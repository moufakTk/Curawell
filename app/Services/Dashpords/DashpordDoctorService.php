<?php

namespace App\Services\Dashpords;

use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Appointments\Waiting\WaitingStatus;
use App\Enums\Payments\BallStatus;
use App\Enums\Sessions\SessionDoctorStatus;
use App\Http\Resources\AppointmentDoctorResource;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\WaitingResource;
use App\Models\Appointment;
use App\Models\AppointmentBill;
use App\Models\Assigned;
use App\Models\Bill;
use App\Models\Competence;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\Doctor_examin;
use App\Models\DoctorEdit;
use App\Models\Patient;
use App\Models\SessionCenter;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Waiting;
use App\Models\WorkDay;
use App\Models\WorkEmployee;
use App\Models\WorkLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DashpordDoctorService
{
    protected $locale;

    public function __construct(){
        $this->locale = App::getLocale();
    }
    public function profileDoctor(User $user)
    {
        $competence_id=WorkLocation::where(['user_id'=>$user->id,"active"=>1])->value('locationable_id');
        $competence_name=Competence::where('id',$competence_id)->value('name_'.$this->locale);
        $user->compentence_name = $competence_name;
        return $user->load('doctor','work_day_time');
    }
    public function treatments(){


       $division= Division::where('doctor_id',auth()->user()->doctor->id)->get()->map(function ($division) {
            return[
                'division_id' => $division->id,
                'small_service' => [
                    'name'  => $division->division_small_service->{'name_'.$this->locale},
                    'price' => $division->division_small_service->price,
                ],
                'discount_rate'=>$division->discount_rate,
            ];
        });

       return $division;

    }
    public function addTreatmentTOSession($request)
    {

        $registered = DB::transaction(function () use ($request) {

            $return =$this->treatmentsInfo($request->treatments ,$request->session_id,$request->with_exam);

            $return1 = $this->total_bill($request->session_id);

            return [
                'data'=>[
                    'discount_percent' => round($return['discount_percent'], 2),
                    'sum_discount'=>$return['sum_discount'],
                    'sum_without_discount'=>$return['sum_without_discount'],
                    'total_treatment_amount'=>$return['total_treatment_amount'],
                    'total_ball'=>$return1
                ]
            ];

        });

        return $registered;
    }
    public function treatmentsInfo($array ,$session_id ,$with_exam){

        $sum_discount=0;               // نسبة الخصم مضروبة بعدد العلاج
        $sum_without_discount=0;              //سعر العلاج مضروب بعدده
        $all_totals=0;
        foreach($array as $item){
            $division=Division::where('id',$item['treatment_id'])->first();
            $price=$division->division_small_service->price;

            $total =($price*$item['quantity'])*(1-$division->discount_rate/100);

            $sum_discount+=$price*$item['quantity']*($division->discount_rate/100);
            $sum_without_discount+=$price*$item['quantity'];
            $all_totals+=$total;
            Treatment::updateOrCreate(
                [
                    'session_center_id' => $session_id,
                    'division_id'       => $item['treatment_id']
                ],
                [
                    'small_service_price' => $price,
                    'small_service_num'   => $item['quantity'],
                    'discount_price'      => $division->discount_rate,
                    'total'               => $total
                ]
            );
        }

        $discount_percent = ($sum_discount / $sum_without_discount) * 100;

        $d_ex = SessionCenter::where('id', $session_id)->first();
        $d_ex->update(['all_discount'=>round($discount_percent, 2)]);
        if($with_exam) {
            $re =$this->amount_Appointment($session_id,$all_totals,$d_ex->doctor_examination);
        }else{
            $re =$this->amount_Appointment($session_id,$all_totals);
        }

        return[
            'discount_percent'=>$discount_percent,
            'sum_discount'=>$sum_discount,
            'sum_without_discount'=>$sum_without_discount,
            'total_treatment_amount'=>$re
        ];


    }
    public function amount_Appointment($session_id,$total ,$doctor_exam =0)
    {

        $session=SessionCenter::where('id',$session_id)->first();
        $b_a =$session->sessionable->appointment_bills()->first();
        $all=$total+$doctor_exam;
        $b_a->update([
            'total_treatment_amount'=>$all
        ]);
        return $all;
    }
    public function total_bill($session_id)
    {
        $session=SessionCenter::where('id',$session_id)->first();
        $ap=$session->sessionable->appointment_bills()->first();

        $bill =Bill::where(['id'=>$ap->bill_id])->first();
        $paid_of_bill=$bill->paid_of_bill;
        $total = AppointmentBill::where('bill_id',$bill->id)->sum('total_treatment_amount');

        $bill->update([
            'total_bill'=>$total
        ]);

        $this->test_status_bill($bill ,$paid_of_bill ,$total);

        return $total;
    }
    public function test_status_bill($bill,$paid_of_bill ,$total_bill)
    {

        if($total_bill > $paid_of_bill){
            $bill->update([
                'status'=>BallStatus::Incomplete
            ]);
        }

    }

    public function addEdit($request)
    {

        $session =SessionCenter::where('id',$request->session_id)->first();
        $a_b =$session->sessionable->appointment_bills()->first();
        $bill=Bill::where('id',$a_b->bill_id)->first();

        $d_e =DoctorEdit::create([
            'bill_id'=>$bill->id,
            'doctor_id'=>auth()->user()->doctor->id,
            "price"=>$request->price,
            'edit'=>$request->edit,
        ]);

        $bill->update(['total_bill'=>$bill->total_bill+$request->price]);
        return $d_e;

    }
    public function updateEdit( $request)
    {

        $d =DoctorEdit::where('id',$request->doctorEdit_id)->first();
        $p=$d->price;
        if(!$d){
            return [
                'success'=>false,
                'message'=>'ما موجود هالاضافة ',
                'data'=>[]
            ];
        }
        $d->update([
            'price'=>$request->price??$d->price,
            'edit'=>$request->edit??$d->edit,
        ]);
       // $p_new=$d->price;

        $d->doctorEdit_bill->update([
            'total_bill'=>$d->doctorEdit_bill->total_bill+$request->price-$p,
        ]);
        return [
            'success'=>true,
            'message'=>'تم التعديل',
            'data'=>$d
        ];
    }
    public function deleteEdit($request)
    {
        $d=DoctorEdit::where('id',$request->doctorEdit_id)->first();
        if(!$d){
            return [
                'success'=>false,
                'message'=>'ما موجود هالاضافة ',
                'data'=>[]
            ];
        }
        $d->doctorEdit_bill->update([
            'total_bill'=>$d->doctorEdit_bill->total_bill-$d->price
        ]);

        $d->delete();

        return [
            'success'=>true,
            'message'=>'تم الحذف',
            'data'=>$d
        ];

    }
    public function reserved_sessions()
    {

        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>auth()->user()->id])->first();
        $doctor_session = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->with('appointments.appointment_patient.patient_user')->get();
        $count = $doctor_session->count();


        return [
            'apointments_reserved'=>AppointmentResource::collection($doctor_session),
            'patient_today'=>$count,
            'patient_don'=>$this->num_appointments_don(),
            'all_patients'=>$this->num_all_patients(),
        ];
        //return $doctor_session;
    }
    public function num_all_patients()
    {

        $num_patients =Assigned::where(['assignedable_type'=>Doctor::class, "assignedable_id"=>auth()->user()->doctor->id])->whereNotNull('id')->count();
        return $num_patients;
    }
    public function num_appointments_don()
    {
        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>auth()->user()->id])->first();
        $appointment_don = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Don);
        })->with('appointments.appointment_patient.patient_user')->get();

        $count=$appointment_don->count();
        return $count;
    }
    public function appointments_occur()
    {

        //return AppointmentResource::collection($this->get_appointment_occur());
        return [
            'appointments'=>AppointmentResource::collection($this->get_appointment_occur()),
            'waiting'=> $this->get_waiting_occur()
        ];
    }
    public function get_appointment_occur()
    {
        $time_now = Carbon::today('Asia/Damascus')->toDateString();

        $workDay=WorkDay::where('history',$time_now)->first();
        $workEmployee =WorkEmployee::where(['work_day_id'=>$workDay->id ,'user_id'=>auth()->user()->id])->first();
        $appointment_occur = $workEmployee->doctor_sessions()->where('status', SessionDoctorStatus::Reserved)->whereHas('appointments',function ($q){
            $q->where('status',AppointmentStatus::Occur);
        })->with('appointments.appointment_patient.patient_user')->get()->each(function ($q){
            $q->Kind='Appointment';
        });

        return $appointment_occur;

    }
    public function get_waiting_occur()
    {
        $time_now = Carbon::today()->toDateString();

        $waiting= Waiting::where(['doctor_id'=>auth()->user()->doctor->id  ,'status'=>WaitingStatus::Occur])->whereDate('created_at',$time_now)->with('waiting_patient','sesstions')->get();
        return WaitingResource::collection($waiting);
        //return $waiting;
    }
    public function doctor_patients($doc)
    {
        $doctor =$doc;
        if(is_null($doctor)){
            $doctor=auth()->user()->doctor;
        }

        $patients_id = Assigned::where(['assignedable_type'=>Doctor::class, "assignedable_id"=>$doctor->id])
            ->pluck('patient_id')
            ->toArray();

        $patients =Patient::whereIn('id', $patients_id)->with('patient_user')->get();

        return [
            'num_patients'=>$doctor->doctor_user->num_patients,
            'patients'=>PatientResource::collection($patients),
        ];

    }
    public function all_appointments_doctor()
    {


        $appointment =Appointment::where('doctor_id',auth()->user()->doctor->id)->with('sesstions','appointment_doctor','appointment_patient')->get()->each(function ($appointment) {
            $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
            $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
            $appointment->department=$competence_name;

            $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
            $appointment->time =$appointment->appointment_doctor_session->from;
            //$appointment->type_serv ='Clinic';
            $appointment->bill=$appointment->appointment_bills()->first()->total_treatment_amount;
            $appointment->paid_bill=$appointment->appointment_bills()->first()->paid_of_amount;
        });
        $num_app=$this->number_appointment();

        return[
            'appointment_reserved'=>$num_app['appointment_reserved'],
            'appointment_done'=>$num_app['appointment_done'],
            'appointments'=>$appointment->map(function ($item) {
                return new AppointmentDoctorResource($item, 'Doctor');
            })
            ];

    }
    public function appointment_doctor_patient($patient)
    {


        $appointment =Appointment::where(['doctor_id'=>auth()->user()->doctor->id,'patient_id'=>$patient->id])->with('sesstions','appointment_doctor','appointment_patient')->get()->each(function ($appointment) {
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
                return new AppointmentDoctorResource($item, 'Doctor');
            });

    }
    public function number_appointment()
    {

       $num_app_res= Appointment::where(['doctor_id'=>auth()->user()->doctor->id])->count();
       $num_app_don=Appointment::where(['doctor_id'=>auth()->user()->doctor->id,'status'=>AppointmentStatus::Don])->count();
       return [
           'appointment_reserved'=>$num_app_res,
           'appointment_done'=>$num_app_don,
       ];
    }
    public function add_info_session($request)
    {
        $session=SessionCenter::where('id',$request->session_id)->first();

        $newDiagnosis = $request->diagnosis ?? [];

        $updatedDiagnosis = [
            'report'      => $newDiagnosis['report'] ?? null,
            'description' => $newDiagnosis['description'] ?? null,
        ];

        $session->update([
            'session_name' => $request->filled('diagnosis_name') ? $request->diagnosis_name : $session->session_name,
            'diagnosis'    => $updatedDiagnosis,
            'medicines'    => $request->filled('medicines') ? $request->medicines : $session->medicines,
        ]);

        if ($request->has('diagnosis_name')) {
            $this->add_on_new_diseases($session,$request->diagnosis_name);
        }

        return $session;

    }
    public function add_on_new_diseases($session ,$newDisease)
    {

        $appointment = $session->sessionable;

        if($appointment instanceof Appointment){
            $md=$appointment->appointment_patient->medical_history;
            $newDiseases = $md->new_diseases ?? [];
            $newDiseases[] = $newDisease;
            $md->new_diseases = $newDiseases;
            $md->save();
        }elseif ($appointment instanceof Waiting){
            $md=$appointment->waiting_patient->medical_history;
            $newDiseases = $md->new_diseases ?? [];
            $newDiseases[] = $newDisease;
            $md->new_diseases = $newDiseases;
            $md->save();
        }

    }




}
