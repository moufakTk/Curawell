<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Models\SessionCenter;
use App\Services\Dashpords\DashpordDoctorService;
use Illuminate\Http\Request;

class DashpordDoctorController extends Controller
{
    //
    protected $dashpordDoctorService;

    public function __construct(DashpordDoctorService $dashpordDoctorService){
        $this->dashpordDoctorService = $dashpordDoctorService;
    }


    public function session_info(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',

        ]);

    }


}
