<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Http\Requests\WaitingRequest;
use App\Models\Appointment;
use App\Services\Dashpords\DashpordSecretaryService;
use App\Services\Dashpords\ForAllService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DashpordSecretaryController extends Controller
{
    //

    protected $dashpordSecretaryService;
    Protected $forAllService;
    public function __construct(DashpordSecretaryService $dashpordSecretaryService , ForAllService $forAllService)
    {
        $this->dashpordSecretaryService = $dashpordSecretaryService;
        $this->forAllService = $forAllService;
    }

    public function reserve_appointment_waiting(WaitingRequest $request)
    {

        $re =$this->dashpordSecretaryService->reserve_appointment_waiting($request);
        return response()->json($re);
    }
    public function update_appointment(Request $request){

        $request->validate([
            'appointment_id'=>'required|exists:appointments,id',
            'new_session_id'=>'required|exists:doctor_sessions,id',

        ]);

        $re = $this->dashpordSecretaryService->update_appointment($request);
        return response()->json($re);

    }
    public function delete_appointment(Request $request)
    {

        $request->validate([
            'appointment_id'=>'required|exists:appointments,id',
            'styl_canceled'=>'required|string|in:FromPatient,FromDoctor',
        ]);

        $re = $this->dashpordSecretaryService->delete_appointment($request);
        return  response()->json($re);

    }
    public function delete_waiting(Request $request)
    {

        $request->validate([
            'waiting_id'=>'required|exists:waitings,id',
        ]);

        $re =$this->dashpordSecretaryService->delete_waiting($request);
        return  response()->json($re);
    }
    public function send_message_delete_taxi(Appointment $appointment){

        $re =$this->dashpordSecretaryService->send_message_delete_taxi($appointment);
        return response()->json($re);

    }
    public function Forbidden_day_doctor(Request $request)
    {
        $request->validate([
            'date'=>'required|date',
            'doctor_id'=>'required|integer|exists:doctors,id'
        ]);
        $re=$this->dashpordSecretaryService->Forbidden_day_doctor($request);
        return response()->json($re);

    }
    public function secretary_queue()
    {
        $re =$this->dashpordSecretaryService->secretary_queue();
        return response()->json($re);

    }
    public function make_appointment_occur(Request $request)
    {

        $request->validate([
            'kind' => ['required', 'string', Rule::in(['Waiting', 'Appointment'])],
            'appointment_id' => [
                'required',
                Rule::when($request->kind === 'Waiting',
                    Rule::exists('waitings', 'id'),
                    Rule::exists('appointments', 'id')
                ),
            ],
        ]);
        $re =$this->dashpordSecretaryService->make_appointment_occur($request);
        return response()->json($re);

    }
    public function secretary_queue_appointment_doctor()
    {

        $re =$this->dashpordSecretaryService->secretary_queue_appointment_doctor();
        return response()->json($re);

    }
    public function secretary_queue_checkOut()
    {
        $re =$this->dashpordSecretaryService->secretary_queue_checkOut();
        return response()->json($re);
    }
     public function make_appointment_checkout(Request $request)
     {

         $request->validate([
             'kind' => ['required', 'string', Rule::in(['Waiting', 'Appointment'])],
             'appointment_id' => [
                 'required',
                 Rule::when($request->kind === 'Waiting',
                     Rule::exists('waitings', 'id'),
                     Rule::exists('appointments', 'id')
                 ),
             ],
         ]);

        $re =$this->dashpordSecretaryService->make_appointment_checkout($request);
        return response()->json($re);
     }
    public function make_appointment_don(Request $request)
    {

        $request->validate([
            'kind' => ['required', 'string', Rule::in(['Waiting', 'Appointment'])],
            'appointment_id' => [
                'required',
                Rule::when($request->kind === 'Waiting',
                    Rule::exists('waitings', 'id'),
                    Rule::exists('appointments', 'id')
                ),
            ],
        ]);

        $re =$this->dashpordSecretaryService->make_appointment_don($request);

        return response()->json($re);
    }

    public function bill_for_appointment(Request $request)
    {

        $request->validate([
            'kind' => ['required', 'string', Rule::in(['Waiting', 'Appointment'])],
            'appointment_id' => [
                'required',
                Rule::when($request->kind === 'Waiting',
                    Rule::exists('waitings', 'id'),
                    Rule::exists('appointments', 'id')
                ),
            ],
        ]);

        $re =$this->dashpordSecretaryService->bill_for_appointment($request);
        return response()->json($re);

    }
    public function update_paid_of_appointment(Request $request)
    {
        $request->validate([
            'kind' => ['required', 'string', Rule::in(['Waiting', 'Appointment'])],
            'appointment_id' => [
                'required',
                Rule::when($request->kind === 'Waiting',
                    Rule::exists('waitings', 'id'),
                    Rule::exists('appointments', 'id')
                ),
            ],
            'edit_amount'=>'required|numeric'
        ]);

        $re =$this->dashpordSecretaryService->update_paid_of_appointment($request);
        return response()->json($re);
    }
    public function update_paid_of_bill(Request $request)
    {
        $request->validate([
            'bill_id'=>'required|exists:bills,id',
            'amount_paid'=>'required|numeric'
        ]);

        $re =$this->dashpordSecretaryService->update_paid_of_bill($request);
        return response()->json($re);
    }
    public function update_status_bill(Request $request)
    {
        $request->validate([
            'bill_id'=>'required|exists:bills,id',
        ]);

        $re=$this->dashpordSecretaryService->update_status_bill($request);
        return response()->json($re);

    }

    public function secretary_patients()
    {
        $re=$this->dashpordSecretaryService->secretary_patients();
        return response()->json($re);
    }

    public function all_appointment_secretary()
    {
        $re=$this->dashpordSecretaryService->all_appointment_secretary();

    }




}
