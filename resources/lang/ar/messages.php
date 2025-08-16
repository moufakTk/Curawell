<?php

return [
    'register_success' => 'تم تسجيل المستخدم بنجاح.',
    'register_failed' => 'فشل تسجيل المستخدم.',
    'login_success'    => 'تم تسجيل الدخول بنجاح.',
    'code_invalid'     => 'رمز التحقق غير صالح.',
    'server_error'     => 'حدث خطأ ما، يرجى المحاولة لاحقاً.',
    'code_send_successfully'=>'تم ارسال الكود بنجاح. ',
    'Incorrect_credentials' => 'رقم الهاتف أو كلمة المرور غير صحيحة.',
    'preFace_success'=>'تعريف حول مركزنا',

    /* getRecords / CenterInfoController */
    'doctor_null'=>'لا يوجد أطباء حالياً',
    'patient_null'=>'لا يوجد مرضى حالياً',
    'nurse_null'=>'لا يوجد ممرضين حالياً',
    'all_have_data'=>' توجد بيانات ',

    /* getSection / CenterInfoController */
    'section_null'=>'لا يوجد أقسام',
    'section_exist'=>'تم ارجاع كل الأقسام',

    /* getClinics / CenterInfoController */
    'Section_not_exist'=>'خدمة العيادات غير موجودة',
    'Clinics_not_exist'=>'لا يوجد عيادات حاليا',
    'Clinics_exist'=>'تم ارجاع العيادات بنجاح',

    /* contactUs / CenterInfoController */
    'contact_success'=>'تم ارجاع معلومات التواصل بنجاح',

    /* doctorTop / CenterInfoController */
    'doctor_is_evaluation'=>'تم ارجاع التقييمات الأعلى',
    'doctor_is_not_evaluation'=>'لا يوجد أطباء تم تقييمها',

    /* comments / CenterInfoController */
    'comment_exist'=>'تم ارحاع تعليقات المركز بنجاح',
    'comment_not_exist'=>'لا يوجد تعليقات على المركز لعرضها',
    'comment_section'=>'هذا الطبيب اما غيرموجود أو لايوجد لديه تعليقات',
    'comment_doctor'=>'هذا الطبيب اما غيرموجود أو لايوجد لديه تعليقات',

    /* articles / CenterInfoController */
    'article_exist'=>'تم ارحاع المقالات بنجاح',
    'article_not_exist'=>'لا يوجد مقالات لعرضها حاليا',

    /* offers / CenterInfoController */
    'discount_exist'=>'تم ارجاع العروض بنجاح',
    'discount_not_exist'=>'لا يوجد عروض لعرضها',

    /* frequentlyQuestion / CenterInfoController */
    'question_not_exist'=>'لايوجد أسئلة حاليا',
    'question_exist'=>'تم إرجاع الأسئلة بنجاح',

    /* dayAndSession / AppointmentService */
    'doctor_not_exist'=>'الطبيب غير موجود',
    'name_day'=>'يوم الجمعة والسبت لايوجد دوام',
    'success_return'=>'تم ارجاع اليوم وفتراته بنجاح',

    /* reserveAppointment / AppointmentService */
    'session_not_available'=>'هذه الفترة ليست متاحة',
    'doctor_periods'=>'هذه الفترة ليست لهذا الطبيب',
    'success_reserve'=>'تم حجز الموعد بنجاح',

    /* services / HomeCareService */
    'services_not_found'=>'لا توجد خدمات حاليًا أو لا يوجد في هذا القسم أي خدمات',
    'services_found'=>'تم ارجاع خدمات القسم بنجاح',

    /* nurseHomeCare / HomeCareService */
    'nurse_not_exist'=>'لايوجد ممرضين لهذه الخدمة حاليا',
    'nurse_exist'=>'تم ارجاع معلومات الممرضين بنجاح',

    /* periodsHomeCare / HomeCareService */
    'date_not_found'=>'هذا التاريخ غير موجود',
    'period_found'=>'تم ارجاع الفترات لهذا اليوم بنجاح',


     /* reserveAppointmentHomeCare / HomeCareService */
    'nurse_gender_not_exist'=>'لا يوجد ممرضين من هذا الجنس',
    'period_not_for_history'=>'هذه الفترة ليست لهذا التاريخ',
    'appointment_time'=>'هذا المستخدم يحوي بالفعل حجز في هذا التاريخ والوقت',
    'appointment_status'=>'الحجز في هذه الفترة غير متاح حاليا، الرجاء اختيار فترة اخرى او المحاولة في وقت لاحق',
    'reserve_success'=>'تم حجز الموعد بنجاح',

    /*  dashboardNurseController*/
    'all_sessions' => ' كل جلسات الممرض مع التفاصيل',
    'session' => 'تم عرض تفاصيل الجلسة',
    'appointment_HomeCare_updated'=>'تم تعديل معلومات الحجز',
    'all_appointments' => 'كل الحجوزات مع التفاصيل ',
    'all_patients'=> 'كل المرضى يلي عند هاد الحجي يلي تعالجو من قبل ويلي لح يتعالجو لقدام .',

    /*  dashboardReceptionController*/
    'reception_create_patient'=>'تم انشاء مريض جديد',
    'reception_search_patient'=>'معلومات المريض',
    'reception_create_sample'=>' تم انشاء العينة بنجاح .',
    'reception.patient.samples.empty'=>'لا يوجد عينات لهذا المريض .',
    'reception.patient.samples'=>'تم عرض كل عينات المريض .',
    'reception.patient.sample.not'=>'هذه العينة ليست لهذا المريض .',
    'reception.patient.sample.update'=>'تم تعديل معلومات هذه العينة .',
    'reception.patient.samples.deleted'=>'تم حذف العينة.',

    'reception' => [
        'analyze_orders' => [
            'list'    => 'تم جلب طلبات التحاليل بنجاح.',
            'show'    => 'تم جلب طلب التحليل بنجاح.',
            'created' => 'تم إنشاء طلب التحليل بنجاح.',
            'updated' => 'تم تحديث طلب التحليل بنجاح.',
            'deleted' => 'تم حذف طلب التحليل بنجاح.',
        ],
        'radiology' =>[
                'services' => [
                    'list'    => 'تم جلب خدمات التصوير بنجاح.',
                    'show'    => 'تم جلب خدمة التصوير بنجاح.',
                    'created' => 'تم إنشاء خدمة التصوير بنجاح.',
                    'updated' => 'تم تحديث خدمة التصوير بنجاح.',
                    'deleted' => 'تم حذف خدمة التصوير بنجاح.',
                    'not' => 'هذه الخدمة ليست متاحة لهذا القسم.',
                                ],
                'doctors' => [
                    'list' => 'تم جلب دكاترة التصوير الشعاعي .'
                             ],
                'patients' => [
                    'orderList' => 'تم جلب كل طلبات التصوير الخاصة بهذا المريض .',
                    'orderListNot' => 'لا يوجد اي طلب تصوير لهذا المريض .',
                    'not'=>'هذا الطلب ليست لهذا المريض .',
                    'created' => 'تم إنشاء طلب خدمة التصوير بنجاح.',
                    'updated' => 'تم تحديث طلب خدمة التصوير بنجاح.',
                    'deleted' => 'تم حذف طلب خدمة التصوير بنجاح.',
            ]
        ]
    ],
    // ar





    'reception.analyses'=>'تم عرض كل التحاليل ',



    'DoctorLab' => [
        'analyze_orders' => [
            'status'    => 'تم تغيير حالة الطلب بنجاح.',
            'show'    => 'تم جلب طلب التحليل بنجاح.',
            'created' => 'تم إنشاء طلب التحليل بنجاح.',
            'updated' => 'تم تحديث طلب التحليل بنجاح.',
            'deleted' => 'تم حذف طلب التحليل بنجاح.',
            'deleted_reports.missing_reports'=>'لا يمكن حذف هذا التقرير لانه غير تابع لهذا التحليل معرف التقرير :'
        ],
    ],


];
