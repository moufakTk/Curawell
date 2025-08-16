<?php

namespace App\Http\Controllers\Dashpords;

use App\Enums\Appointments\appointment\AppointmentHomeCareStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\NurseSession;
use App\Services\Dashpords\DashpordNurseService;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class DashpordNurseController extends Controller
{
    //
    protected $dashpordNurseService;

    public function __construct(DashpordNurseService $dashpordNurseService){
        $this->dashpordNurseService = $dashpordNurseService;
    }
public function sessions(){
    try {

        $data =  $this->dashpordNurseService->sessions();
        return ApiResponse::success($data,__('messages.all_sessions'),200);

    }catch (\Exception $exception){

        return response()->json($exception->getMessage(),500);
}


}

public function showSession(Request $request){
        $request->validate([
            'id'=>'required|exists:nurse_sessions,id',
        ]);
    try {

        $data = $this->dashpordNurseService->showSession($request->id);
      return ApiResponse::success($data['data'],$data['message'],200);


    }catch (\Exception $exception){
        return ApiResponse::error($exception->getMessage(),$exception->getCode());
    }
}

public function updateAppointment(Request $request){
        $request->validate([
            'id'=>'required|exists:appointment_home_cares,id',
            'cost'=>'nullable|numeric',
            'report'=>'nullable|string',
            'status' => ['nullable', Rule::enum(AppointmentHomeCareStatus::class)],

        ]);
    try {
$data = $this->dashpordNurseService->updateAppointment($request);
return ApiResponse::success($data,__('messages.appointment_HomeCare_updated'),200);
    }catch (\Exception $exception){
        return ApiResponse::error($exception->getMessage(),$exception->getCode()??500);
    }
}
// منعرض المواعيد القادمة مع الاعداد
public function appointments(){
    try {
        $data =  $this->dashpordNurseService->appointments();
        return ApiResponse::success($data,__('messages.all_appointments'),200);

    }catch (\Exception $exception){
        return ApiResponse::error($exception->getMessage(),$exception->getCode()??500);
    }

}

// منعرض كل المواعيد يلي ساواها
public function completedAppointments(){
    try {
        $data =  $this->dashpordNurseService->completedAppointments();
        return ApiResponse::success($data,__('messages.all_appointments'),200);

    }catch (\Exception $exception){
        return ApiResponse::error($exception->getMessage(),$exception->getCode()??500);
    }

}

public function appointmentsCount(){
    try {
        $data =  $this->dashpordNurseService->appointmentsCount();
        return ApiResponse::success($data,__('messages.all_appointments'),200);

    }catch (\Exception $exception){
        return ApiResponse::error($exception->getMessage(),$exception->getCode()??500);
    }

}

// منعرضلو المرضي يلي عندو وعمرهم واخر زيارة وان في عندو زيارة قادمة
public function patients(){
    try {
        $data = $this->dashpordNurseService->patients();
        return  ApiResponse::success($data,__('messages.all_patients'),200);

    }catch (\Exception $exception){
        return ApiResponse::error(null,$exception->getMessage(),$exception->getCode()??500);
    }
}
}
