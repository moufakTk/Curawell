<?php

namespace App\Services\Dashpords;

use App\Models\Competence;
use App\Models\User;
use App\Models\WorkLocation;
use Illuminate\Support\Facades\App;

class DashpordDoctorService
{
    protected $locale;

    public function __construct(){
        $this->locale = App::getLocale();
    }
    public function profileDoctor(User $user)
    {
        $competence_id=WorkLocation::where(['user_id'=>$user->id,"active"=>1])->value('locationable_id');
        $competence_name=Competence::where('id',$competence_id)->value('name_'.$this->locale);
        $user->compentence_name = $competence_name;
        return $user->load('doctor','work_day_time');
    }


}
