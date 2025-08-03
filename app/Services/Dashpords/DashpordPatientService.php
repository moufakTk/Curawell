<?php

namespace App\Services\Dashpords;

use App\Models\User;

class DashpordPatientService
{


    public function profilePatient(User $user)
    {

        return $user->load('patient.medical_history');
    }

}
