<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Dashpords\DashpordLabDoctorController;
use App\Http\Controllers\Dashpords\DashpordNurseController;
use App\Http\Controllers\Dashpords\DashpordReceptionController;
use App\Http\Controllers\Dashpords\ForAllController;
use App\Http\Middleware\Language\SetLocaleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/{user}', function (\App\Models\User $user) {
    return $user;
})->middleware('auth:sanctum');
Route::middleware(SetLocaleMiddleware::class)->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::post('/auth/google/callback', 'loginWithGoogle')->name('loginWithGoogle');
//        Route::get('/auth/google/callback', 'callback');
//        Route::get('/auth/google/redirect', 'redirect');

    });

    Route::prefix('auth')->group(function () {
        Route::controller(VerificationController::class)->group(function () {
            Route::post('/send-code', 'sendCode');
            Route::post('/verify-code', 'verifyCode');

        });
        Route::post('/reset-password', [PasswordController::class,'resetPassword']);   // إعادة تعيين كلمة المرور
    });
});


Route::post('/register', [AuthController::class, 'register'])->middleware(SetLocaleMiddleware::class);

Route::post('create/user',[\App\Http\Controllers\Admin\CRUDController::class, 'createUser']);




Route::middleware('auth:sanctum')->group(function () {

                                       //  Patient
    Route::post("/reserve_appointment" ,[\App\Http\Controllers\Appointment\AppointmentController::class,'reserveAppointment']);
    Route::post('/reserve_appointment_HC' ,[\App\Http\Controllers\Appointment\HomeCareController::class,'reserveAppointmentHomeCare']);
    Route::get('profile',[\App\Http\Controllers\Dashpords\ForAllController::class,'profile']);
    Route::get("/myDoctors" ,[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'myDoctors']);
    Route::get('/sessions',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'sessions']);
    Route::get("/appointments",[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'appointments'])->name('patient_appointments');
    Route::get('/all_appointments',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'allAppointments'])->name('patient_appointments');
    Route::get('/get_points',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'my_points']);
    Route::post('/evaluction',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'evaluction']);
    Route::post('/updateProfilePatient',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'updateProfile']);
    Route::post('/addComment',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'addComment']);
    Route::post('/updateComment',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'updateComment']);
    Route::post('/deleteComment',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'deleteComment']);
    Route::post('/complaint',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'complaint']);



                                        // Doctor

    Route::get('/treatmentDoctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'treatments']);
    Route::post('/addTreatmentDoctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'addTreatmentTOSession']);
    Route::post("/addEdit",[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'addEdit']);
    Route::post('/updateEdit',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'updateEdit']);
    Route::post('/deleteEdit',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'deleteEdit']);

    Route::get('/reserved_sessions',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'reserved_sessions'])->name('doctor_appointments');
    Route::get('/num_patients',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'num_all_patients']);

});
                                    /* home page & landing page  */
Route::get('/Info-center' ,[\App\Http\Controllers\CenterInfoController::class ,'getInfo'])->name('settings.Info');
Route::get('/Info-contact_us' ,[\App\Http\Controllers\CenterInfoController::class ,'contactUs'])->name('settings.contactUs');
Route::get("/get_record_user",[\App\Http\Controllers\CenterInfoController::class,'getRecords']);
Route::get("/get_sections" ,[\App\Http\Controllers\CenterInfoController::class,'getSections']);
Route::post("/get_clinics" ,[\App\Http\Controllers\CenterInfoController::class,'getClinics']);
Route::get("/get_Top_doctors" ,[\App\Http\Controllers\CenterInfoController::class,'doctorTop'])->name('doctors.index');
Route::post("/get_comments" ,[\App\Http\Controllers\CenterInfoController::class,'comments'])->name('patient.index');
Route::get('/get_articles' ,[\App\Http\Controllers\CenterInfoController::class,'articles']);
Route::get('/get_discounts' ,[\App\Http\Controllers\CenterInfoController::class,'offers']);

                                    /*  Clinics page  */
Route::get('/get_questions' ,[\App\Http\Controllers\CenterInfoController::class,'frequentlyQuestion']);

                                    /*  Services page  */
Route::post('/doctors_services',[\App\Http\Controllers\Appointment\AppointmentController::class,'getDoctorServices'])->name('doctor.services');
Route::post('/competences_services',[\App\Http\Controllers\Appointment\AppointmentController::class,'competences'])->name('competences.service');
Route::post('/competence_doctors',[\App\Http\Controllers\Appointment\AppointmentController::class,'competenceDoctors']);
Route::post('/doctor_status',[\App\Http\Controllers\Appointment\AppointmentController::class,'doctorStatus']);
Route::post('/service-offers',[\App\Http\Controllers\Appointment\AppointmentController::class,'serviceOffers']);
Route::post("/day_and_sessions" ,[\App\Http\Controllers\Appointment\AppointmentController::class,'dayAndSession']);

                                    /*  Home Care Page */
Route::post('/services_section',[\App\Http\Controllers\Appointment\HomeCareController::class,'services']);
Route::get("/nurses_homeCare" ,[\App\Http\Controllers\Appointment\HomeCareController::class,'nurseHomeCare']);
Route::post('/period_homeCare',[\App\Http\Controllers\Appointment\HomeCareController::class,'periodsHomeCare'])->name('period_to_patient');


                                        /* Dashboards */
Route::prefix('/dashboard')->middleware(['auth:sanctum',SetLocaleMiddleware::class])->group( function () {

    Route::get('patients/{patient}/skiagraph_orders',[DashpordReceptionController::class,'showPatientSkiagraphOrders']);
    Route::get('patients/skiagraph_orders', [DashpordReceptionController::class,'showPatientSkiagraphOrders']);

    Route::get('/patient/{patient}/analyses',[DashpordLabDoctorController::class,'patientAnalyses']);
    Route::get('/patient/analyses',[DashpordLabDoctorController::class,'patientAnalyses']);

                            /* admin dashboard */
    Route::prefix('/admin')->group( function () {
    });

                            /* Doctor dashboard */
    Route::prefix('/doctor')->group( function () {
    });

    Route::controller(DashpordLabDoctorController::class)->middleware(['role:Doctor_lab'])->prefix('/lab-doctor')->group( function () {


        Route::get('/pending-analyses','pendingAnalyses');
        Route::get('/analyses','Analyses');
        Route::get('/complete-analyses','completeAnalyses');
        Route::get('/count-analyses','countAnalyses');
        Route::post('/update-analyses/{analyzeOrder}','updateAnalyses');

    });

                            /* Nurse dashboard */
    Route::controller(DashpordNurseController::class)->middleware(['role:Nurse'])->prefix('/nurse')->group( function () {
             Route::get('/profile',[ForAllController::class,'profile']);
             Route::get('/sessions','sessions')->name('nurse.sessions');
             Route::get('/session','showSession')->name('nurse.show.session');
             Route::post('/update-appointment','updateAppointment');
             Route::get('/appointments','appointments')->name('nurse.appointments');
             Route::get('/completed-Appointments','completedAppointments')->name('nurse.completed.appointments');
             Route::get('/appointments-count','appointmentsCount')->name('nurse.completed.appointments');
             Route::get('/patients','patients')->name('nurse.patients');


    });

                            /* reception dashboard*/
    Route::controller(DashpordReceptionController::class)->prefix('/reception')->middleware(['role:Reception'])->group( function () {

                            /*  register a new Patient  */
        // 1. تسجيل مريض جديد
        Route::post('/patients', 'registerPatient');

// 2. البحث عن مريض (مثلاً باستخدام ?patient_num=)
        Route::get('/patients', 'searchPatient');


        // 3. إنشاء نموذج لعينة جديدة
        Route::get   ('/analyses', 'showAnalyses');

        // انشاء العينات
        Route::post  ('/patients/{patient}/samples/create',            'createSample');
        Route::get   ('/patients/{patient}/samples',                    'showSamples');
        Route::post  ('/patients/{patient}/samples/{sample}/update',   'updateSample');
        Route::delete('/patients/{patient}/samples/{sample}/delete', 'deleteSample');
//انشاء التحاليل
        Route::get   ('/patients/analyze-orders',          'showPatientsAnalyses');
        Route::get   ('/patients/{patient}/analyze-orders',          'showPatientAnalyses');
        Route::post  ('/patients/{patient}/analyze-orders',          'createPatientAnalyse');
        Route::get   ('/patients/{patient}/analyze-orders/{order}',  'showPatientAnalyse' );
        Route::post  ('/patients/{patient}/analyze-orders/{order}',  'updatePatientAnalyse' );
        Route::delete('/patients/{patient}/analyze-orders/{order}',  'deletePatientAnalyse' );

        //انشاء التصوير
        Route::get('/radiology/services',  'radiologyServices' );
        Route::get('/radiology/services/{service}',  'showRadiologyServices' );

        Route::get('patients/skiagraph_orders/count','countSkiagraphOrders');
        Route::get('patients/skiagraph_orders','showSkiagraphOrders');
        // patients CRUD
       // Route::get('patients/{patient?}/skiagraph_orders','showPatientSkiagraphOrders');
        Route::get('patients/{patient}/skiagraph_orders/{order}','showPatientSkiagraphOrder');
        Route::post('patients/{patient}/skiagraph_orders/create','createPatientSkiagraphOrder');
        Route::post('patients/{patient}/skiagraph_orders/{order}/update','updatePatientSkiagraphOrder');
        Route::delete('patients/{patient}/skiagraph_orders/{order}/delete','deletePatientSkiagraphOrder');



    });




});



