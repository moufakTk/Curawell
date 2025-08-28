<?php

use App\Http\Controllers\Admin\Articles\ArticleController;
use App\Http\Controllers\Admin\Comments\CommentController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\Discounts\DiscountController;
use App\Http\Controllers\Admin\FrequentlyQuestionController;
use App\Http\Controllers\Admin\Sections\CompetenceController;
use App\Http\Controllers\Admin\Sections\DivisionController;
use App\Http\Controllers\Admin\Sections\SectionController;
use App\Http\Controllers\Admin\Sections\ServiceController;
use App\Http\Controllers\Admin\Sections\SmallServiceController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\WorkDay\WorkDayController;
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
        Route::post('/auth/google/callbackk', 'loginWithGoogle')->name('loginWithGoogle');
        Route::get('/auth/google/callback', 'callback');
        Route::get('/auth/google/redirect', 'redirect');

    });

    Route::prefix('auth')->group(function () {
        Route::controller(VerificationController::class)->group(function () {
            Route::post('/send-code', 'sendCode');
            Route::post('/verify-code', 'verifyCode');

        });
        Route::post('patient/update-missing-info',[AuthController::class,'updateMissingInfo'])->middleware('auth:sanctum');
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
    Route::get('/my_appointment_bills',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'my_appointment_bills']);
    Route::get('/my_bill_hc',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'my_bill_hc']);
    Route::get('/my_bill_analyze',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,"my_bill_analyze"]);
    Route::get('/my_bill_skiagraph',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,"my_bill_skiagraph"]);
    Route::get('/rates_bill',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'rates_bill']);
    Route::get('/profilePatient/{user}',[\App\Http\Controllers\Dashpords\DashpordPatientController::class,'profilePatient']);

                                        // Doctor

    Route::get('/treatmentDoctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'treatments']);
    Route::post('/addTreatmentDoctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'addTreatmentTOSession']);
    Route::post("/addEdit",[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'addEdit']);
    Route::post('/updateEdit',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'updateEdit']);
    Route::post('/deleteEdit',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'deleteEdit']);
    Route::get('/reserved_sessions',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'reserved_sessions'])->name('doctor_appointments');
    Route::get('/num_patients',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'num_all_patients']);
    Route::get('/doctor/appointments_occur',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'appointments_occur'])->name('doctor_appointments');
    Route::get('/doctor_patients/doctor/{$doctor}',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'doctor_patients']);
    Route::get('/doctor_patients/doctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'doctor_patients']);
    Route::get('/all_appointments_doctor',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'all_appointments_doctor']);
    Route::get('/number_appointment',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'number_appointment']);
    Route::get('/appointment_doctor_patient/{patient}',[\App\Http\Controllers\Dashpords\DashpordDoctorController::class,'appointment_doctor_patient']);


                                    //  Secretary
    Route::post('/reserve_appointment_waiting',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'reserve_appointment_waiting']);
    Route::post('/update_appointment' ,[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'update_appointment']);
    Route::post("/delete_appointment" ,[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'delete_appointment']);
    Route::post('/delete_waiting',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'delete_waiting']);
    Route::get('/send_message_delete_taxi/{appointment}',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'send_message_delete_taxi']);
    Route::post('/Forbidden_day_doctor',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'Forbidden_day_doctor']);
    Route::get('/secretary_queue',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'secretary_queue']);
    Route::post('/make_appointment_occur',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'make_appointment_occur']);
    Route::post('/make_appointment_checkout',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'make_appointment_checkout']);
    Route::post('/make_appointment_don',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'make_appointment_don']);
    Route::get('/secretary_queue_appointment_doctor',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'secretary_queue_appointment_doctor']);
    Route::get('/secretary_queue_checkOut',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,"secretary_queue_checkOut"]);
    Route::post('/bill_for_appointment',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'bill_for_appointment']);
    Route::post('/update_paid_of_appointment',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'update_paid_of_appointment']);
    Route::post('/update_paid_of_bill',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,"update_paid_of_bill"]);
    Route::post('/update_status_bill',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,"update_status_bill"]);
    Route::get('/secretary_patients',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'secretary_patients']);
    Route::get('/all_appointment_secretary',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'all_appointment_secretary']);
    Route::get('/appointment_secretary_patient/{patient}',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'appointment_secretary_patient']);
    Route::get('/bill_patient_secretary/{patient}',[\App\Http\Controllers\Dashpords\DashpordSecretaryController::class,'bill_patient_secretary']);


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

    Route::get('/patientAnalysesDon/{patient}',[DashpordLabDoctorController::class,'patientAnalysesDon']);

                            /* admin dashboard */
    Route::prefix('admin')
//        ->middleware(['role:Admin']) // عدّل حسب نظامك (abilities/permissions)
        ->group(function () {

            Route::controller(SectionController::class)->prefix('/sections')->group(function () {
                Route::get('/',  'index');
                Route::get('/type',  'getType');
                Route::post('/', 'store');
                Route::get('{section}',  'show');
                Route::put('{section}',  'update');
                Route::delete('{section}',  'destroy');
                Route::post('{section}/image',  'uploadSectionImage');

            });
                // Services (للعيادات + الهوم كير)
            // ===== Services =====
            Route::prefix('services')->name('admin.services.')->controller(ServiceController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('{service}', 'show')->name('show');
                    Route::put('{service}', 'update')->name('update');
                    Route::delete('{service}', 'destroy')->name('destroy');

                    // Media (صورة واحدة + فيديو واحد)
                    Route::post('{service}/image', 'uploadServiceImage')->name('upload-image');
                    Route::post('{service}/video', 'uploadServiceVideo')->name('upload-video');
                });
            // ===== Competences =====
            Route::prefix('competences')->name('admin.competences.')->controller(CompetenceController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('{competence}', 'show')->name('show');
                    Route::put('{competence}', 'update')->name('update');
                    Route::delete('{competence}', 'destroy')->name('destroy');
                });
            // ===== small-services =====
            Route::prefix('small-services')->name('admin.small-services.')->controller(SmallServiceController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('{smallService}', 'show')->name('show');
                    Route::put('{smallService}', 'update')->name('update');
                    Route::delete('{smallService}', 'destroy')->name('destroy');
                });
            // ===== divisions =====
            Route::prefix('divisions')->name('admin.divisions.')->controller(DivisionController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('{division}', 'show')->name('show');
                    Route::put('{division}', 'update')->name('update');
                    Route::delete('{division}', 'destroy')->name('destroy');

                    // خصم (اختياري)
                    Route::patch('{division}/toggle-discount', 'toggleDiscount')->name('toggle-discount');
                });

            Route::prefix('work-days')->name('admin.work-days.')->controller(WorkDayController::class)->group(function () {
                Route::post('/open', 'open')->name('open');           // فتح فترة
                Route::get('/', 'index')->name('index');              // استعراض
                Route::get('/summary', 'summary')->name('summary');   // ملخص
                Route::patch('/{workDay}/toggle', 'toggle')->name('toggle');      // تبديل حالة
                Route::patch('/{workDay}/status', 'setStatus')->name('set-status'); // ضبط حالة
                Route::post('/auto-toUp', 'autoTopUp')->name('auto');    // تعبئة تلقائية عند الطلب

            });
            Route::prefix('articles')->name('admin.articles.')->controller(ArticleController::class)->group(function () {
                    Route::get('/', 'index')->name('index');     // GET    /api/admin/articles
                    Route::post('/', 'store')->name('store');    // POST   /api/admin/articles
                    Route::get('{article}', 'show')->name('show'); // GET  /api/admin/articles/{article}
                    Route::put('{article}', 'update')->name('update'); // PUT /api/admin/articles/{article}
                    Route::post('{article}', 'update');         // PATCH  /api/admin/articles/{article}
                    Route::delete('{article}', 'destroy')->name('destroy'); // DELETE /api/admin/articles/{article}

                    Route::patch('{article}/toggle', 'toggle')->name('toggle');
                });
            Route::prefix('frequently-questions')->name('admin.fq.')->controller(FrequentlyQuestionController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{frequentlyQuestion}', 'show')->name('show');
                Route::put('{frequentlyQuestion}', 'update')->name('update');
                Route::delete('{frequentlyQuestion}', 'destroy')->name('destroy');
                Route::patch('{frequentlyQuestion}/toggle', 'toggle')->name('toggle');
            });

            Route::controller(ComplaintController::class)->prefix('/complaints')->group(function () {
                Route::get('/',  'index');
                Route::get('{complaint}',  'show');
                Route::put('{complaint}',  'update');
                Route::delete('{complaint}',  'destroy');

            });

            Route::controller(CommentController::class)->prefix('comments')->name('admin.comments.')->group(function () {
                Route::get('/',  'index')->name('index');
                Route::get('/{comment}', 'show')->name('show');
                Route::put('/{comment}/toggle', 'toggle')->name('approve');
                Route::delete('/{comment}','destroy')->name('destroy');
            });

            Route::controller(DiscountController::class)->prefix('discounts')->name('admin.comments.')->group(function () {
                Route::get('/',  'index')->name('index');
            /*👍*/    Route::get('/searchDoctors',  'searchDoctors')->name('searchDoctors');
            /*👍*/    Route::post('/doctors-services', 'getDoctorsServices')->name('getDoctorsServices');
            /* ~ */   Route::post('/', 'create')->name('create');
                Route::get('/{discount}', 'show')->name('show');
                Route::delete('/{discount}', 'delete')->name('delete');
//                Route::put('/discounts/{discount}/toggle', 'toggle')->name('toggle');
            });

            Route::controller(UserController::class)
                ->prefix('/users')
                ->group(function () {
                    // إنشاء مستخدم جديد
                    Route::post('/', 'store');

                    // جلب أماكن العمل حسب نوع المستخدم
                    Route::post('/work-locations', 'getWorkLocationByUserType');
                });
        });

                            /* Doctor dashboard */
    Route::prefix('/doctor')->group( function () {
    });

    Route::controller(DashpordLabDoctorController::class)->middleware(['role:Doctor_lab'])->prefix('/lab-doctor')->group( function () {


        Route::get('/pending-analyses','pendingAnalyses');
        Route::get('/patient/{patient}/analyses','patientAnalyses');
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
        Route::get('/patients/{patient}/homeCare-appointments', 'all_app_homeCare')->name('patient_appointments');
        Route::get('/patients/{patient}/clinic-appointments', 'all_app_clinic')->name('patient_appointments');
        Route::get('/patients/{patient}/information', 'patientInformation');
        Route::get('/patients/{patient}/reports', 'patientReports');



        // 3. إنشاء نموذج لعينة جديدة
        Route::get   ('/analyses', 'showAnalyses');

        // انشاء العينات
        Route::get   ('/samples',                    'showSamplesType');


        Route::post  ('/patients/{patient}/samples/create',          'createSample');
        Route::get   ('/patients/{patient}/samples',                 'showPatientSamples');
        Route::get   ('/patients/samples',                           'showSamples');
        Route::post  ('/patients/{patient}/samples/{sample}/update', 'updateSample');
        Route::delete('/patients/{patient}/samples/{sample}/delete', 'deleteSample');
//انشاء التحاليل
        Route::get   ('/patients/analyze-orders',                    'showPatientsAnalyses');
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


        Route::get('patients/{patient}/skiagraph_orders','showPatientSkiagraphOrders');
        Route::get('patients/{patient}/skiagraph_orders/{order}','showPatientSkiagraphOrder');
        Route::post('patients/{patient}/skiagraph_orders/create','createPatientSkiagraphOrder');
        Route::post('patients/{patient}/skiagraph_orders/{order}/update','updatePatientSkiagraphOrder');
        Route::delete('patients/{patient}/skiagraph_orders/{order}/delete','deletePatientSkiagraphOrder');
    });


});



