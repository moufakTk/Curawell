<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Services\Dashpords\DashpordNurseService;
use Illuminate\Http\Request;

class DashpordNurseController extends Controller
{
    //
    protected $dashpordNurseService;

    public function __construct(DashpordNurseService $dashpordNurseService){
        $this->dashpordNurseService = $dashpordNurseService;
    }



}
