<?php

namespace App\Services\Dashpords;

use App\Models\Section;
use App\Models\User;
use App\Models\WorkLocation;
use Illuminate\Support\Facades\App;

class DashpordNurseService
{
    protected $locale;
    public function __construct(){
        $this->locale = App::getLocale();
    }

    public function profileNurse(User $user)
    {

        $section_id=WorkLocation::where(['user_id'=>$user->id ,'active'=>1])->value('locationable_id');
        $section_name=Section::where('id',$section_id)->value('name_'.$this->locale);
        $user->section_name = $section_name;
        return $user->load('work_day_time') ;
    }


}
