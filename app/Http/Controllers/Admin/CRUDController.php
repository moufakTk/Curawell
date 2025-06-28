<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Users\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Http\Requests\RegisterDoctorRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\CRUDService;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    //

    protected $CRUDService;

    public function __construct(CRUDService $CRUDService){
        $this->CRUDService = $CRUDService;
    }


    public function createUser(Request $request)
    {

        $user_type = UserType::tryFrom($request->input('user_type'));

        if (!$user_type) {
            throw new \InvalidArgumentException('نوع مستخدم غير صالح');
        }

        $a = match ($user_type) {
            UserType::Doctor => $this->CRUDService->registerDoctor(app(RegisterDoctorRequest::class)),
            default => $this->CRUDService->registerUser(app(RegisterUserRequest::class)),
        };
        return response()->json([
            'massage'=> __('messages.register_success'),
            'data'=>$a



        ]);

    }


}
