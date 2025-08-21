<?php

namespace App\Services\Dashpords;

use App\DTO\PointsDTO;
use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Services\SectionType;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\PointsResource;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\Bill;
use App\Models\Comment;
use App\Models\Competence;
use App\Models\Complaint;
use App\Models\Doctor;
use App\Models\Evaluction;
use App\Models\Section;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserReplacement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class DashpordPatientService
{

    protected $locale;
    public function __construct(){
        $this->locale = App::getLocale();
    }


    public function profilePatient(User $user)
    {
        return $user->load('patient.medical_history');
    }

    public function myDoctors()
    {
        $patient =auth()->user()->patient;

        $minIds = Appointment::where('patient_id', $patient->id)
            ->where('status', '!=', AppointmentStatus::Cancel)
            ->selectRaw('MIN(id) as id')
            ->groupBy('doctor_id');

        $appointments = Appointment::whereIn('id', $minIds)
            ->with('appointment_doctor.doctor_user.active_work_location')
            ->get();

        $appointments->each(function ($appointment) {
            $d =$appointment->appointment_doctor->doctor_user->active_work_location;

            $c=Competence::where('id',$d->locationable_id)->value('name_'.$this->locale);
            $appointment->appointment_doctor->doctor_user->competence_name =$c;

        });

        return $appointments->pluck('appointment_doctor.doctor_user')->map(function ($doctor) {
            $doctor->makeHidden(['active_work_location']);
            return $doctor->only(['id', 'first_name', 'last_name', 'competence_name']);
        });

    }

    public function sessions()
    {

        $appointments= Appointment::where('status',AppointmentStatus::Don)->whereHas('appointment_patient' ,function ($q){
            $q->where('id',auth()->user()->patient->id);
        })->with(['sesstions' => function ($query) {
            $query->where('sessionable_type', Appointment::class)
                ->with('treatments');
        }])
        ->get()->map(function ($appointment) {

            $date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
            $time =$appointment->appointment_doctor_session->from;
            $sessionDateTime = Carbon::parse("$date $time");

            $diffInSeconds = now()->diffInSeconds($sessionDateTime);
            $days = floor($diffInSeconds / 86400);
            $hours = floor(($diffInSeconds % 86400) / 3600);
            $minutes = floor(($diffInSeconds % 3600) / 60);
            $appointment->date_ago ='d:'.$days.'|h:'.$hours.'|m:'.$minutes;
            $appointment->date_appointment=$sessionDateTime->format('Y-m-d H:i:s');

            return $appointment;

        })->sortBy('date_appointment');

        return $appointments;
    }

    public function appointment_future()
    {
        $appointment =Appointment::where('patient_id',auth()->user()->patient->id)
            ->whereIn('status',[AppointmentStatus::Confirmed ,AppointmentStatus::Occur])
            ->with('appointment_doctor.doctor_user' )
            ->get()
            ->each(function ($appointment) {

                    $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
                    $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
                    $appointment->department=$competence_name;

                    $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
                    $appointment->time =$appointment->appointment_doctor_session->from;
                    $appointment->type_serv ='Clinic';
            });

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'Clinic',false);
        });

    }

    public function appointment_don()
    {
        $appointment =Appointment::where(['patient_id'=>auth()->user()->patient->id ,'status'=>AppointmentStatus::Don])
            ->with('appointment_doctor.doctor_user' )
            ->get()->each(function ($appointment) {

                $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
                $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
                $appointment->department=$competence_name;

                $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
                $appointment->time =$appointment->appointment_doctor_session->from;
                $appointment->type_serv ='Clinic';
            });;

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'Clinic' ,false);
        });
    }

    public function appointment_hc_future()
    {

        $appointment =AppointmentHomeCare::where('patient_id',auth()->user()->patient->id)
            ->whereIn('status',[AppointmentHomeCareStatus::Scheduled])
            ->with('appointment_home_session_nurse.nurse','appointment_home_session_nurse.session_day')
            ->get()
            ->each(function ($q){
                $q->type_serv ='HomeCare';
            });

        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'HomeCare' ,false);
        });
    }

    public function appointment_hc_don()
    {

        $appointment =AppointmentHomeCare::where(['patient_id'=>auth()->user()->patient->id,'status'=>AppointmentHomeCareStatus::Completed])
            ->with('appointment_home_session_nurse.nurse','appointment_home_session_nurse.session_day')
            ->get()
            ->each(function ($q){
                $q->type_serv ='HomeCare';
            });
        if($appointment->isEmpty()){
            return 'مافي قيم ';
        }
        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'HomeCare' ,false);
        });

    }

    public function  all_app_clinic()
    {
        $appointment =Appointment::where('patient_id',auth()->user()->patient->id)
            ->with('appointment_doctor.doctor_user','sesstions' )
            ->get()->each(function ($appointment) {
                $location_id=$appointment->appointment_doctor->doctor_user->active_work_location->locationable_id;
                $competence_name=Competence::where('id',$location_id)->value('name_'.$this->locale);
                $appointment->department=$competence_name;

                $appointment->date =$appointment->appointment_doctor_session->session_doctor->work_employee_Day->history;
                $appointment->time =$appointment->appointment_doctor_session->from;
                $appointment->type_serv ='Clinic';
        });

        if($appointment->isEmpty()){
            return 'ما في قيم';
        }

        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'Clinic' ,true);
        });

        }

    public function all_app_homeCare()
    {
        $appointment =AppointmentHomeCare::where('patient_id',auth()->user()->patient->id)
            ->with('appointment_home_session_nurse.nurse','appointment_home_session_nurse.session_day')
            ->get()
            ->each(function ($q){
                $q->type_serv ='HomeCare';
            });
        if($appointment->isEmpty()){
            return ' ما في قيم';
        }

        return $appointment->map(function ($item) {
            return new AppointmentResource($item, 'HomeCare' ,true);
        });

    }

    public function my_points()
    {

        $sum=0;
        $sum_re=0;

        $all_poitns=UserPoint::where('patient_id',auth()->user()->patient->id)->with('point_point')->get()->each(function ($q) use(&$sum){
            $sum+=$q->point_number;
        });
        //$all_poitns->sum_points=$sum;


        $all_re_points=UserReplacement::where('patient_id',auth()->user()->patient->id)->with('user_rep_appointment','user_rep_replacement')->get()->each(function ($q) use(&$sum_re){
            $sum_re+=$q->replace_point_num;
        });

        $dto=new PointsDTO(sum_points: $sum,sum_point_replaced: $sum_re,points: $all_poitns,points_replaced: $all_re_points);

        return new PointsResource($dto);


    }

    public function evaluction($request)
    {
        $patient = auth()->user()->patient->id;

        $evalution = Evaluction::where(['patient_id' => $patient, 'doctor_id' => $request->doctor_id])->first();
        if (!$evalution) {
             Evaluction::create([
                'doctor_id' => $request->doctor_id,
                'patient_id' => $patient,
                'stars_number' => $request->number,
            ]);

        }else{
            $evalution->update(['stars_number'=>$request->number]);
        }

        $eva = Evaluction::where('doctor_id', $request->doctor_id)->get();
        $sum_evalution = $eva->sum('stars_number');
        $count = $eva->whereNotNull('id')->count();
        $des=$sum_evalution / $count;
        $intPart =floor($des);
        $decimalPart =$des-$intPart;
        $doctor=Doctor::where('id',$request->doctor_id)->first();

        if ($decimalPart < 0.5) {
            $final = $intPart;
        } else {
            $final = $intPart + 0.5;
        }

        $doctor->update(['evaluation'=>$final]);

        return [
            'success'=>true,
            'message'=>'تم التقييم بنجاح',
            //'1'=>$sum_evalution,
            //'2'=>$count,
            'data'=>$final
        ];


    }

    public function updateProfile($request)
    {

        $user=User::where('id',auth()->user()->id)->first();
        $user->update([
            'first_name'=>$request->first_name??$user->first_name,
            'last_name'=>$request->last_name??$user->last_name,
            'address'=>$request->address??$user->address,
            'gender'=>$request->gender??$user->gender,
            'birthday'=>$request->birthday??$user->birthday,
        ]);

        $m_h=$user->patient->medical_history;
        $m_h->update([
            'chronic_diseases'=>$request->chronic_diseases??$m_h->chronic_diseases,
            'hereditary_diseases'=>$request->hereditary_diseases??$m_h->hereditary_diseases,
            'new_diseases'=>$request->new_diseases??$m_h->new_diseases,
            'allergies'=>$request->allergies??$m_h->allergies,
            'blood_group'=>$request->blood_group??$m_h->blood_group,
            'weight'=>$request->weight??$m_h->weight,
            'height'=>$request->height??$m_h->height,
        ]);


        return $user->load('patient.medical_history');

    }

    public function addComment($request)
    {

        if($request->comment_type =='HomeCare') {
            $comment=Comment::create([
                'patient_id'=>auth()->user()->patient->id,
                'commentable_type'=>Section::class,
                'commentable_id'=>Section::where('section_type',sectionType::HomeCare)->value('id'),
                'comment'=>$request->comment
            ]);
        }
        elseif ($request->comment_type=="Center"){
            $comment=Comment::create([
                'patient_id'=>auth()->user()->patient->id,
                'commentable_type'=>null,
                'commentable_id'=>null,
                'comment'=>$request->comment
            ]);
        }else{
            $comment=Comment::create([
                'patient_id'=>auth()->user()->patient->id,
                'commentable_type'=>Doctor::class,
                'commentable_id'=>$request->id,
                'comment'=>$request->comment
            ]);
        }

        return $comment;

    }

    public function updateComment($request)
    {

        $comment =Comment::where(['id'=>$request->comment_id ,'patient_id'=>auth()->user()->patient->id])->first();
        if(!$comment){
            return $comment ;
        }

        $comment->update([
            'comment'=>$request->comment,
            'status'=>false
        ]);

        return $comment;
    }

    public function deleteComment($request)
    {

        $comment =Comment::where(['id'=>$request->comment_id ,'patient_id'=>auth()->user()->patient->id])->first();
        if(!$comment){
            return $comment ;
        }

        $comment->delete();
        return $comment;
    }

    public function complaint($request)
    {

       $competence= Complaint::create([
           'patient_id'=>auth()->user()->patient->id ,
           'complaint'=>$request->complaint,
           'styl_reply'=>$request->type,
           'email'=>$request->type=='email' ? $request->value :null,
           'phone'=>$request->type=='phone' ? $request->value :null,
       ]);

       return $competence;

    }



//    public function ()
//    {
//
//    }





}
