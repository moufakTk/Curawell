<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ComplaintResource;
use App\Models\Complaint;
use App\Services\Admin\ComplaintService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{

    public function __construct(private ComplaintService $complaintService){
    }
    public function index(request $request){
        $request->validate([
            'reply'=>['boolean']
        ]);
        $complaints=null;
        $replied = Complaint::where('reply',1)->count();
        $unReplied = Complaint::where('reply',0)->count();
        if($request->has('reply')){
          $complaints =          Complaint::where('reply',1)->get();
            $complaints=$complaints->map(function($complaint){
                return new ComplaintResource($complaint);

            });

        }else{
        $complaints = Complaint::all();
            $complaints=$complaints->map(function($complaint){
                return new ComplaintResource($complaint);

            })->groupBy('reply');

        }
        $data=[
            'data'=>$complaints,
            'replied'=>$replied,
            'unReplied'=>$unReplied
        ];
        return ApiResponse::success($data,'Complaint List',200);
    }
    public function show(Complaint $complaint){
        $complaint=new ComplaintResource($complaint);
       return ApiResponse::success($complaint,'Complaint List',200);
    }
    public function update(Request $request,Complaint $complaint){
        $request->validate([
            'message_reply'=>['required','string','min:10'],
        ]);
        try {
            $data = $this->complaintService->update($request,$complaint);
            $data = new ComplaintResource($data);
           return ApiResponse::success($data,'Complaint Update',200);

        }catch (\Exception $exception){
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }


    }
    public function destroy(complaint $complaint){
        $this->complaintService->delete($complaint);
       return ApiResponse::success([],'Complaint Delete',200);

    }

}
