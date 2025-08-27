<?php

namespace App\Http\Resources;

use App\Models\Appointment;
use App\Models\Waiting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    protected $locale ,$kind;
    public function __construct($resource ,$kind =null)
    {
        parent::__construct($resource);
        $this->locale=app()->getLocale();
        $this->kind=$kind;
    }



    public function toArray(Request $request): array
    {
        return match ($this->kind){

            'Secretary'=>
            [
                'bill_id'=>$this->id,
                'bill_num'=>$this->private_num,
                'total_bill'=>$this->total_bill,
                'paid_of_bill'=>$this->paid_of_bill,
                'status'=>$this->status,
                'session_id'=>$this->session_id,
                'extra_doctor'=>$this->doctorEdits->map(function($edit){
                    return [
                        'extra_id'=>$edit->id,
                        'edit'=>$edit->edit,
                        'price'=>$edit->price
                    ];
                }),
                'bill_appointments'=>$this->appointment_bills->map(function ($bill){
                    return[
                        'appointment_bill_id'=>$bill->id,
                        'total_bill'=>$bill->total_treatment_amount,
                        'paid_bill'=>$bill->paid_of_amount
                    ];
                })

            ],

            "Patient"=>[
                'bill_id'=>$this->id,
                'bill_num'=>$this->private_num,
                'total_bill'=>$this->total_bill,
                'paid_of_bill'=>$this->paid_of_bill,
                'status'=>$this->status,
                'doctor_name'=>optional($this->bill_doctor)->getFullNameAttribute(),
                'extra_doctor'=>$this->doctorEdits->map(function($edit){
                    return [
                        'extra_id'=>$edit->id,
                        'edit'=>$edit->edit,
                        'price'=>$edit->price,

                    ];
                }),
                'bill_appointments'=>$this->appointment_bills->map(function ($bill){
                    $appointable = $bill->appointable;
                    if($appointable instanceof Appointment){
                        return[
                            'appointment_bill_id'=>$bill->id,
                            'total_bill'=>$bill->total_treatment_amount,
                            'paid_bill'=>$bill->paid_of_amount,
                            'date'=>optional($appointable->appointment_doctor_session->session_doctor->work_employee_Day)->history,
                            'time'=>optional($appointable->appointment_doctor_session)->from,
                        ];
                    }elseif ($appointable instanceof Waiting){
                        return [
                            'appointment_bill_id'=>$bill->id,
                            'total_bill'=>$bill->total_treatment_amount,
                            'paid_bill'=>$bill->paid_of_amount,
                            'date'=>$appointable->created_at,
                        ];

                    }
                    return null;
                }),


            ],

        };
    }
}
