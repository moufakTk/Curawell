<?php

namespace App\Services\Dashpords;

use App\Http\Resources\Dashboards\AppointmentHomeCareResource;
use App\Http\Resources\Dashboards\NurseResource;
use App\Models\Appointment;
use App\Models\AppointmentHomeCare;
use App\Models\NurseSession;
use App\Models\Section;
use App\Models\User;
use App\Models\WorkDay;
use App\Models\WorkLocation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
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

        return new AppointmentHomeCareResource($session);
//        return $session;
    }
    public function updateAppointment($request){

        $data = DB::transaction(function () use ($request) {
            $appointment=AppointmentHomeCare::OwnedByNurse(auth()->user())->where('id',$request->id)->first();
            if(!$appointment){
                throw new \Exception('Unauthorized access to session',401);
            }
$appointment->update([
    'status'=>$request->status??$appointment->status,
    'price'=>$request->cost??$appointment->price,
    'explain'=>$request->report??$appointment->explain,
]);


return $appointment;

        });

return $data;
 }


    public function appointments()
    {
        $appointments = AppointmentHomeCare::ownedByNurse(auth()->user())->get();

        $grouped = $appointments->groupBy('type');

        return $grouped;
    }



    public function profileNurse(User $user)
    {

        $section_id=WorkLocation::where(['user_id'=>$user->id ,'active'=>1])->value('locationable_id');
        $section_name=Section::where('id',$section_id)->value('name_'.$this->locale);
        $user->section_name = $section_name;
        return $user->load('work_day_time') ;
    }


}
