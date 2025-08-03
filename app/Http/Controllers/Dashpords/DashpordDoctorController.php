<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Services\Dashpords\DashpordDoctorService;
use Illuminate\Http\Request;

class DashpordDoctorController extends Controller
{
    //
    protected $dashpordDoctorService;

    public function __construct(DashpordDoctorService $dashpordDoctorService){
        $this->dashpordDoctorService = $dashpordDoctorService;
    }




}
