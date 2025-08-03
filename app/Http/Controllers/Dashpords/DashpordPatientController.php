<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
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




}
