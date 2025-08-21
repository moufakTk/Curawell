<?php

namespace App\Services\Dashpords;


use App\Enums\Users\UserType;
use App\Models\User;

class ForAllService
{

    protected $dashpordPatientService;
    protected $dashpordDoctorService;
    protected $dashpordNurseService;

    public function __construct(DashpordPatientService $dashpordPatientService , DashpordDoctorService $dashpordDoctorService , DashpordNurseService $dashpordNurseService){
        $this->dashpordPatientService = $dashpordPatientService;
        $this->dashpordDoctorService = $dashpordDoctorService;
        $this->dashpordNurseService = $dashpordNurseService;
    }


    public function profile()
    {

        $user= auth()->user();
 if (!$user)
     return response()->json(['error' => 'asdasdaskdljasdkl'], 401);
        return match ($user->user_type){

            UserType::Patient => $this->dashpordPatientService->profilePatient($user),
            UserType::Doctor => $this->dashpordDoctorService->profileDoctor($user),
            UserType::Nurse=>$this->dashpordNurseService->profileNurse($user),
            default => abort(404),
        };



    }





}
