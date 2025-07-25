<?php

namespace App\Http\Controllers;

use App\Enums\Services\SectionType;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\getRecordUsersResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\TopDoctorResource;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Discount;
use App\Models\Doctor;
use App\Models\FrequentlyQuestion;
use App\Models\Patient;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class CenterInfoController extends Controller
{
    protected $locale;

    public function __construct(){
        $this->locale=app()->getLocale();
    }

    //
    public function getInfo(Request $request)
    {

//       $setting =Setting::select( 'id' ,
//                        'site_name',
//                        'preface_'.$this->locale ,
//                        'wise_'.$this->locale ,
//            )->first();

        return response()->json([
            'success'=>true ,
            'massage'=>__('messages.preFace_success'),
            'data'=>new SettingResource(Setting::first()) ,
            'lang'=>$this->locale,
        ]);
    }


    public function contactUs(Request $request){

        return response()->json([
            'success'=>true,
            'message'=>__('messages.contact_success'),
            'data'=>new SettingResource(Setting::first()),

        ]);
    }

    public function getRecords(Request $request){

        $massages=[];

        $patients =Patient::count();
        if($patients ==0){
            $massages[]=__('messages.patient_null');
        }

        $nurse =User::where('user_type' ,UserType::Nurse)->count();
        if($nurse ==0){
            $massages[]=__('messages.nurse_null');
        }

        $doctors=Doctor::count();
        if($doctors ==0){
            $massages[]=__('messages.doctor_null');
        }


        return new getRecordUsersResource([
            'patient'=>$patients,
            'nurse'=>$nurse,
            'doctor'=>$doctors
        ] , $massages);
    }


    public function getSections(Request $request)
    {

        $section = Section::select('id',
                                    'name_'.$this->locale,
                                        'brief_description_'.$this->locale,
                                        'section_type'
                            )->get();

        if($section->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.section_exist'),
                'data'=>$section
            ]);
        }

        return response()->json([
            'success' => false,
            'message'=>__('messages.section_null'),
            'data'    => []
        ]);

    }

    public function getClinics(Request $request)
    {
        $section=Section::where('section_type',sectionType::Clinics)->value('id');
        if($section==null){
            return response()->json([
                'success' => false,
                'message'=>__('messages.Section_not_exist'),
                'data'    => []
            ]);
        }

        $services =Service::where('section_id' ,$section)
                ->select('id',
                        'name_'.$this->locale,
                        'details_services_'.$this->locale,
                        )
                ->get();
        if($services->isEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.Clinics_not_exist'),
                'data'=>[]
            ]);
        }
        return response()->json([
            'success' => true,
            'message'=>__('messages.Clinics_exist'),
            'data'=>$services
        ]);
    }

    public function frequentlyQuestion(Request $request)
    {

        $questions =FrequentlyQuestion::where('status' ,1)
                          ->select('id',
                                   'question_'.$this->locale,
                                   'answer_'.$this->locale,
                                   'status'
                          )
                          ->get();


        if($questions->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message' =>__('messages.question_exist'),
                'data'=>$questions
            ]);
        }

        return response()->json([
            'success' => false,
            'message'=>__('messages.question_not_exist'),
            'data'=>[]
        ]);
    }


    public function doctorTop(Request $request)
    {
        $doctor_evaluation=Doctor::where([['doctor_type','=',DoctorType::Clinic] ,['evaluation','!=',0]])
                                ->with('doctor_user')->orderBy('evaluation' ,'desc')
                                ->limit(5)
                                ->get();

        if($doctor_evaluation->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.doctor_is_evaluation'),
                'data'=> TopDoctorResource::collection($doctor_evaluation),
            ]);
        }
        return response()->json([
            'success' => false,
            'message'=>__('messages.doctor_is_not_evaluation'),
            'data'=>[]
        ]);

    }

    public function comments(Request $request)
    {

        $comments = Comment::where([['commentable_type','=',null],['status','=',1]])->with('comment_patient','comment_patient.patient_user')->get();

        if($comments->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.comment_exist'),
                'data'=> CommentResource::collection($comments),
            ]);
        }
        return response()->json([
            'success' => false,
            'message'=>__('messages.comment_not_exist'),
        ]);
    }

    public function articles(Request $request)
    {

        $articles=Article::where("is_active",1)
                        ->select('id',
                                'title_'.$this->locale,
                                'brief_description_'.$this->locale,
                                'path_link','is_active')
                        ->get();

        if($articles->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.article_exist'),
                'data'=>$articles
            ]);
        }
        return response()->json([
            'success' => false,
            'message'=>__('messages.article_not_exist'),
            'data'=>[]
        ]);
    }

    public function offers(Request $request)
    {

        $discounts=Discount::where('active' ,1)
                            ->select('id',
                                     'name_'.$this->locale,
                                     'description_'.$this->locale,
                                     'discount_rate','active')
                            ->get();
        if($discounts->isNotEmpty()){
            return response()->json([
                'success' => true,
                'message'=>__('messages.discount_exist'),
                'data'=>$discounts
            ]);
        }

        return response()->json([
            'success' => false,
            'message'=>__('messages.discount_not_exist'),
            'data'=>[]
        ]);
    }


}
