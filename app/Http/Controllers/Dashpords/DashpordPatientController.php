<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilePatientRequest;
use App\Http\Resources\SessionsResource;
use App\Models\User;
use App\Services\Dashpords\DashpordPatientService;
use Illuminate\Http\Request;

class DashpordPatientController extends Controller
{
    //
    protected $dashpordPatientService;
    public function __construct(DashpordPatientService $dashpordPatientService)
    {
        $this->dashpordPatientService = $dashpordPatientService;
    }


    public function myDoctors()
    {

        $re = $this->dashpordPatientService->myDoctors();
        return response()->json($re);
    }

    public function sessions()
    {

        $re = $this->dashpordPatientService->Sessions();
        return SessionsResource::collection($re);
    }



    public function appointments()
    {

        $re1 =$this->dashpordPatientService->appointment_future();
        $re2 =$this->dashpordPatientService->appointment_don();

        $re3=$this->dashpordPatientService->appointment_hc_future();
        $re4=$this->dashpordPatientService->appointment_hc_don();

        return response()->json([
            'sessions' => true,
            'message'=>'success',
            'appointments' =>[
                'appointment_clinic'=> [
                    'future'=>$re1,
                    'don'=>$re2,
                ] ,
                'appointment_homeCare' =>[
                    'future'=>$re3,
                    'don'=>$re4
                ] ,
            ],
        ]);


    }

    public function allAppointments()
    {
        $re1 =$this->dashpordPatientService->all_app_clinic();
        $re2 =$this->dashpordPatientService->all_app_homeCare();

        return response()->json([
            'success' => true,
            'message'=>'success',
            'appointments' =>[
                'clinic'=>$re1,
                'homeCare'=>$re2,
            ]
        ]);

    }

    public function my_points()
    {
        return response()->json($this->dashpordPatientService->my_points());
    }

    public function evaluction(Request $request)
    {

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'number'=>'required|integer|between:1,5',
        ]);

        $re=$this->dashpordPatientService->evaluction($request);

        return response()->json($re);




    }

    public function updateProfile(UpdateProfilePatientRequest $request)
    {

    $re=$this->dashpordPatientService->updateProfile($request);
    return response()->json($re);

    }

    public function addComment(Request $request)
    {
        $request->validate([
            'comment_type' => 'required|in:HomeCare,Doctor,Center',
            'id' => 'required_if:comment_type,Doctor',
            'comment'=>'required|string',

        ]);

        $re=$this->dashpordPatientService->addComment($request);
        return response()->json([
            'success' => true,
            'message'=>"تم إضافة التعليق بنجاح",
            'data'=>$re
        ]);

    }

    public function updateComment(Request $request)
    {

        $request->validate([
            'comment_id'=>'required|exists:comments,id',
            'comment'=>'required|string',
        ]);

        $re=$this->dashpordPatientService->updateComment($request);

        if(is_null($re)){
            return response()->json([
                'success' => false,
                'message'=>'هذا التعليق غير موجود عند هذا المستخدم',
                'data'=>[]
            ]);
        }

        return response()->json([
            'success' => true,
            'message'=>'تم تعدبل التعليق بنجاح',
            'data'=>$re
        ]);

    }

    public function deleteComment(Request $request)
    {

        $request->validate([
            'comment_id'=>'required|exists:comments,id',
        ]);

        $re =$this->dashpordPatientService->deleteComment($request);

        if(is_null($re)){
            return response()->json([
                'success' => false,
                'message'=>'هذا التعليق غير موجود عند هذا المستخدم',
                'data'=>[]
            ]);
        }

        return response()->json([
            'success' => true,
            'message'=>'تم حذف العنصر بنجاح',
            'data'=>$re
        ]);

    }

    public function complaint(Request $request)
    {

        $request->validate([
            'complaint'=>'required|string',
            'type'  => 'required|in:email,phone',
            'value' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->type === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $fail(__('validation.email', ['attribute' => $attribute]));
                    }
                    if ($request->type === 'phone' && !preg_match('/^[0-9]+$/', $value)) {
                        $fail(__('validation.numeric', ['attribute' => $attribute]));
                    }
                }
            ],

        ]);

        $re=$this->dashpordPatientService->complaint($request);
        return response()->json([
            'success' => true,
            'message'=>'تم ارسال الشكوى بنجاح',
            'data'=>$re
        ]);


    }



    public function  my_appointment_bills()
    {

        $re =$this->dashpordPatientService->my_appointment_bills();
        return response()->json($re);

    }

    public function my_bill_hc()
    {
        $re =$this->dashpordPatientService->my_bill_hc();
        return response()->json($re['bills']);

    }

    public function my_bill_analyze()
    {
        $re =$this->dashpordPatientService->my_bill_analyze();
        return response()->json($re['bills']);
    }

    public function my_bill_skiagraph(){
        $re =$this->dashpordPatientService->my_bill_skiagraph();
        return response()->json($re['bills']);
    }

    public function rates_bill()
    {
        $re=$this->dashpordPatientService->rates_bill();
        return response()->json($re);

    }

        public function profilePatient($user)
    {
        $thisUser = User::findOrFail($user);
        $re=$this->dashpordPatientService->profilePatient($thisUser);
        return response()->json($re);

    }





}
