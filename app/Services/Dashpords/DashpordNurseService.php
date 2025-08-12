<?php

namespace App\Services\Dashpords;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Enums\Appointments\appointment\AppointmentStatus;
use App\Enums\Sessions\SessionNurseStatus;
use App\Http\Resources\Dashboards\AppointmentHomeCareResource;
use App\Http\Resources\Dashboards\NurseResource;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\NurseSession;
use App\Models\Patient;
use App\Models\Section;
use App\Models\User;
use App\Models\WorkDay;
use App\Models\WorkLocation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashpordNurseService
{
    protected $locale;
    public function __construct(){
        $this->locale = App::getLocale();
    }

    public function sessions(){

        $user = auth()->user();
         //جبت العلاقات منشان جيب الايام يلي بداوم فيهن الممرض
        $user->load(['work_employees.work_employee_Day','work_employees.nurse_sessions']);
        // اخدت الايام
        $dayIds = $user->work_employees->pluck('work_day_id')->unique();
        //جبت الابام يلي بيداوم فيها هو مع تفاصيل دوام هل نهار مع الجلسات تبع الممرض
        $days =  WorkDay::whereIn('id',$dayIds)->with(['work_employees' => function ($query) use($user) {
            $query->where('user_id',$user->id);
        },'work_employees.nurse_sessions'])->get();
// تجميع عحسب الشهر
        $days = $days->map(function($day){
            $month = \Carbon\Carbon::parse($day->history)->translatedFormat('F'); //منجيب اسم الشهر عحسب اللغة
            return [
                'id'=> $day->id,
                'month'=> $month,
                "day_en"=> $day->day_en,
                "day_ar"=> $day->day_ar,
                "status"=> $day->status,
                "history"=> $day->history,
                "work_employees" =>$day->work_employees,
            ];
        })->groupBy('month');

 return  new NurseResource($days);

    }

    public function showSession(int $id)

    {
        $session = NurseSession::with([
            'nurse',
            'appointments_home.appointment_home_patient.patient_user',
            'session_day'
        ])->find($id);

        if (!$session) {
            throw new \Exception('Session not found',404);
        }

        if (!$session->nurse || auth()->id() !== $session->nurse->id) {
            throw new \Exception('Unauthorized access to session',401);
        }
 if (!$session->appointments_home)
     return ['data'=>[],
         'message'=>__('messages.null_appointments_home')
     ];
        return ['data'=>new AppointmentHomeCareResource($session),
        'message'=>__('messages.session')
    ];;
//        return $session;
    }
    public function updateAppointment($request){

        $data = DB::transaction(function () use ($request) {
            $appointment=AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())->where('id',$request->id)->first();
            if(!$appointment){
                throw new \Exception('Unauthorized access to session',401);
            }
$appointment->update([
    'status'=>AppointmentHomeCareStatus::Completed,
    'price'=>$request->cost??$appointment->price,
    'explain'=>$request->report??$appointment->explain,
]);
$appointment->appointment_home_session_nurse->update([
    'status'=>SessionNurseStatus::Reserved,
]);

return $appointment;

        });

return $data;
 }


    public function appointments()
    {
        $appointments = AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())
                ->where('status',AppointmentHomeCareStatus::Scheduled)
            ->with('appointment_home_session_nurse.session_day',
                'appointment_home_patient.patient_user')->get();
$appointments = $appointments->map(function($appointment){
    return new AppointmentHomeCareResource($appointment);
});
$completeAppointmentCount =AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())->where('status',AppointmentHomeCareStatus::Completed)->count(); ;

        return [
            'scheduled_appointments'=>$appointments

            ];
    }

    public function appointmentsCount()
    {

        $completeAppointmentCount =AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())->where('status','!=',AppointmentHomeCareStatus::Scheduled)->count();
        $scheduledAppointmentCount =AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())->where('status',AppointmentHomeCareStatus::Scheduled)->count();

        return [
            'scheduled_appointments_count'=>$scheduledAppointmentCount,
            'complete_appointment_count'=>$completeAppointmentCount,

        ];
    }

    public function CompletedAppointments()

    {
        $appointments = AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())
            ->where('status',AppointmentHomeCareStatus::Completed)
            ->with('appointment_home_session_nurse.session_day',
                'appointment_home_patient.patient_user')->get();
        $appointments = $appointments->map(function($appointment){
            return new AppointmentHomeCareResource($appointment);
        });

        return [
            'completed_appointments'=>$appointments,

        ];
    }


public function patients(){

    $appointment =  AppointmentHomeCare::AppointmentsOwnedByNurse(auth()->user())
        ->with(['patient_user'])->get();
    $patientIds = $appointment->pluck('patient_id')->unique();

          $patients =  Patient::whereIn('id',$patientIds)->get();
          $patients = $patients->map(function($patient){

              return[
                  'id'=>$patient->id,
                  'name'=>$patient->full_name,
                  'age'=>$patient->patient_user->age,
                  'last_appointment'=> AppointmentHomeCareResource::appointment($patient->last_appointment) ,
                  'next_appointment'=> AppointmentHomeCareResource::appointment($patient->next_appointment),
              ];
          });
        return ['patients'=>$patients,
            'patients_count'=>$patients->count()];
//        return $patientIds;
}



    public function profileNurse(User $user)
    {

        $section_id=WorkLocation::where(['user_id'=>$user->id ,'active'=>1])->value('locationable_id');
        $section_name=Section::where('id',$section_id)->value('name_'.$this->locale);
        $user->section_name = $section_name;
        return $user->load('work_day_time') ;
    }


}
