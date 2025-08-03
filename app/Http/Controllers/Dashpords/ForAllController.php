<?php

namespace App\Http\Controllers\Dashpords;

use App\Http\Controllers\Controller;
use App\Services\Dashpords\ForAllService;
use Illuminate\Http\Request;

class ForAllController extends Controller
{
    //
    protected $forAllService;

    public function __construct(ForAllService $forAllService){
        $this->forAllService = $forAllService;
    }


    public function profile()
    {

        return response()->json($this->forAllService->profile());
    }

}
