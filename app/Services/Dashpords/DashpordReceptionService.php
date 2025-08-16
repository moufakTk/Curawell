<?php

namespace App\Services\Dashpords;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Enums\Orders\SkiagraphOrderStatus;
use App\Enums\Services\SectionType;
use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Events\WhatsAppAnalysesPatient;
use App\Events\WhatsAppInfoPatient;
use App\Helpers\ApiResponse;
use App\Http\Resources\Analyze\AnalyzeOrderResource;
use App\Models\Analyze;
use App\Models\AnalyzeOrder;
use App\Models\Doctor;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\Sample;
use App\Models\Section;
use App\Models\SkiagraphOrder;
use App\Models\SmallService;
use App\Models\User;
use App\Services\AuthServices\VerificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashpordReceptionService
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function registerPatient($request)
    {
        $password = Str::random(8);

        $registered = DB::transaction(function () use ($request, $password) {
            $birthday = Carbon::parse($request->birthday);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birthday' => $request->birthday,
                'age' => $birthday->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'password' => $password,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_type' => UserType::Patient,
            ]);

            $patient = Patient::create([
                'user_id' => $user->id,
                'civil_id_number' => $request->civil_id_number,
                'patient_num' => str_pad(Patient::max('id') + 1 ?? 0 + 1, 8, '0', STR_PAD_LEFT)
            ]);

            $medical_history = MedicalHistory::create([
                'patient_id' => $patient->id,
            ]);

            $user->assignRole(UserType::Patient->defaultRole());
//            $user->load('roles.permissions');
            return $user;
        });
        event(new WhatsAppInfoPatient($registered, $password));
        $this->verificationService->sendVerificationCode($registered, 'phone', 'verify');
//        $this->verificationService->sendVerificationCode($registered,'email','verify');

        return $registered;


    }

    public function searchPatient($patient_num)
    {
        $patients = null;
        if ($patient_num) {
            $patients = Patient::where('patient_num', "LIKE", "%$patient_num%")->with('patient_user:id,first_name,last_name')->select('id', 'user_id', 'patient_num')->get();
        } else {
            $patients = Patient::with('patient_user')->select('id', 'user_id', 'patient_num')->get();
        }
        $patients = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'patient_num' => $patient->patient_num,
                'name' => $patient->patient_user->full_name,
            ];
        });

        return $patients;

    }

    // -------------------- samples --------------------
    public function createSample($request, $patient)
    {

        $sample = DB::transaction(function () use ($request, $patient) {
            $sample = $patient->samples()->create([
                'sample_type' => $request->sample_type,
                'process_take' => $request->process_take,
                'time_take' => $request->time_take ?? now(),
                'time_don' => $request->time_don ?? now(),
                'status' => $request->status ?? true,
            ]);
            return $sample;
        });

        return $sample;


    }

    public function showSamples($patient)
    {

        $samples = $patient->samples()->where('status', true)->select('sample_type', 'id', 'status')->get();
        if ($samples->isEmpty()) {
            return [
                'data' => [],
                'message' => __('messages.reception.patient.samples.empty'),
            ];
        }
        return [
            'data' => $samples,
            'message' => __('messages.reception.patient.samples'),
        ];
    }

    public function updateSample($request, $patient, $sample): array
    {
        if ($sample->patient_id !== $patient->id) {
            throw new \Exception(__('messages.reception.patient.sample.not'), 404);
        }

        $payload = [];
        if ($request->has('sample_type')) {
            $payload['sample_type'] = $request->input('sample_type') ?? $sample->sample_type; // ممكن تكون null (مسموح حسب الـvalidate)
        }
        if ($request->has('process_take')) {
            $payload['process_take'] = $request->input('process_take') ?? $sample->process_take;
        }


        $sample->update($payload);

        return [
            'data' => $sample->fresh(),
            'message' => __('messages.reception.patient.sample.update'),
        ];
    }

    public function deleteSample($patient, $sample): array
    {
        // تأكيد ملكية العينة للمريض (لو ما كنت مفعل scoped bindings)
        if ($sample->patient_id !== $patient->id) {
            throw new \Exception(__('messages.reception.patient.sample.not'), 404);
        }

        DB::transaction(function () use ($sample) {
            $sample->Delete();      // حذف نهائي

        });

        return [
            'data' => [],
            'message' => __('messages.reception.patient.samples.deleted'),
        ];
    }

    // -------------------- samples --------------------

    // -------------------- analyses --------------------
    public function showPatientsAnalyses()
    {

        $analyses = AnalyzeOrder::where('status', AnalyzeOrderStatus::Pending)
            ->with([
                'analyzed_order_patient.patient_user',      // لاسم المريض
                'AnalyzeRelated.analyzesRelated_analyze',   // عناصر التحاليل
                'samplesRelated.SamplesRelated_sample',     // العينات
            ])->get();
        return [
            'data' => AnalyzeOrderResource::collection($analyses)
            , 'message' => __('messages.reception.analyze_orders.list'),
        ];

    }

    public function showPatientAnalyses($patient)
    {
        $analyses = $patient->analyze_orders()
            ->where('status', AnalyzeOrderStatus::Pending)
            ->with([
                'analyzed_order_patient.patient_user',      // لاسم المريض
                'AnalyzeRelated.analyzesRelated_analyze',   // عناصر التحاليل
                'samplesRelated.SamplesRelated_sample',     // العينات
            ])->get();
        return [
            'data' => AnalyzeOrderResource::collection($analyses)
            , 'message' => __('messages.reception.analyze_orders.list'),
        ];

    }

    public function createPatientAnalyse($request, $patient)
    {
        $data = DB::transaction(function () use ($request, $patient) {
            $analyseOrder = $patient->analyze_orders()->create([
                'doctor_name' => $request->input('doctor_name'),
                'status' => AnalyzeOrderStatus::Pending,
                'name' => $request->input('name'),
                'price' => 0

            ]);
            foreach ($request->analyses as $analyze) {
                $a = Analyze::find($analyze['analyze_id']);
                $analyseOrder->AnalyzeRelated()->create([
                    'analyze_id' => $a->id,
                    'price' => $a->price,
                ]);
                $analyseOrder->price += $a->price;
                $analyseOrder->save();
            }
            foreach ($request->samples as $sample) {
                $s = Sample::find($sample['sample_id']);

                if ($s->patient_id !== $patient->id) {
                    throw new \Exception(__('messages.reception.patient.sample.not') . $s->id, 404);
                }
                if ($s->status !== 1) {
                    throw new \Exception(__('منتهية الصلاحية') . $s->id, 404);
                }
                $analyseOrder->SamplesRelated()->create([
                    'sample_id' => $s->id,
                ]);
                $s->update([
                    'status' => 0
                ]);
            }
            return $analyseOrder->load('SamplesRelated', 'AnalyzeRelated');
        });

        return $data;
    }

    public function deletePatientAnalyse($patient, $analyze)
    {
        $data = DB::transaction(function () use ($patient, $analyze) {
            if ($patient->id !== $analyze->patient_id) {
                throw new \Exception(__('messages.reception.patient.sample.not'), 404);
            }
            if ($analyze->status !== AnalyzeOrderStatus::Pending) {
                throw new \Exception(__('لايمكن حذف هذا التحليل لانه جاري انجازه'), 404);

            }
            $samplesIds = $analyze->samplesRelated()->pluck('sample_id');
            Sample::whereIn('id', $samplesIds)->update([
                'status' => 1,
            ]);
            $analyze->AnalyzeRelated()->forceDelete();
            $analyze->samplesRelated()->forceDelete();
            $analyze->delete();


        });
    }

    public function showAnalyses()
    {
        $analyses = Analyze::get();
        return [
            'data' => $analyses,
            'message' => __('messages.reception.analyses'),
        ];
    }

    // -------------------- analyses --------------------


    // -------------------- radiology --------------------
    public function radiologyServices()
    {

        $section = Section::where('section_type', SectionType::Radiography)->with('small_services')->first();
        $radiologyService = $section->small_services;
        $radiologyService = $radiologyService->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->{'name_' . app()->getLocale()},
                'description' => $item->{'description_' . app()->getLocale()},
                'price' => $item->price,
            ];
        });

        return [
            'data' => $radiologyService,
            'message' => __('messages.reception.radiology.services.list'),
        ];
    }

    public function ShowRadiologyServices($service)
    {

        $section = Section::where('section_type', SectionType::Radiography)->first();
        if ($service->section_id !== $section->id) {
            throw new \Exception(__('messages.reception.radiology.services.not'), 404);
        }
        $radiologyService = $section->small_services()->where('id', $service->id)->get();
        $radiologyService = $radiologyService->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->{'name_' . app()->getLocale()},
                'description' => $item->{'description_' . app()->getLocale()},
                'price' => $item->price,
            ];
        });

        return [
            'data' => $radiologyService,
            'message' => __('messages.reception.radiology.services.list'),
        ];
    }

    public function radiologyDoctors()
    {

        $doctors = Doctor::where('doctor_type', DoctorType::Radiographer)->with('doctor_user')->get();
        $doctors = $doctors->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->doctor_user->full_name,
                'doctor_type' => $item->doctor_type,
            ];
        });
        return [
            'data' => $doctors,
            'message' => __('messages.reception.radiology.doctors,list'),
        ];
    }

    public function showSkiagraphOrders()
    {
        $orders = SkiagraphOrder::with('skaigraph_patient')->get();
        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'patient_name' => $order->skaigraph_patient->patient_user->full_name,
                'doctor_name' => $order->doctor_name,
                'price' => $order->price,
                'status' => $order->status,
                'radiology_image_name' => $order->skaigraph_small_service->{'name_' . app()->getLocale()},
                'radiology_image_description' => $order->skaigraph_small_service->{'description_' . app()->getLocale()},
                'reports' => $order->reports->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'file_path' => $item->file_path,
                    ];
                })
            ];
        })->groupBy('status');
        return [
            'data' => $orders,
            'message' => __('messages.reception.radiology.orderList'),
        ];

    }

    public function countSkiagraphOrders()
    {
        $orders = SkiagraphOrder::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        return [
            'data' => $orders,
            'message' => __('messages.reception.radiology.patients.orderList'),
        ];

    }

    public function showPatientSkiagraphOrders($patient)
    {

        $orders = $patient->skiagraph_Orders()->get();
        if ($orders->isEmpty()) {
            throw new \Exception(__('messages.reception.radiology.patients.orderListNot'), 404);
        }
        $orders = $orders->map(function ($item) {
            return [
                'id' => $item->id,
                'patient_name' => $item->skaigraph_patient->patient_user->full_name,
                'doctor_name' => $item->doctor_name,
                'price' => $item->price,
                'status' => $item->status,
                'radiology_image_name' => $item->skaigraph_small_service->{'name_' . app()->getLocale()},
                'reports' => $item->reports->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'file_path' => $item->file_path,
                    ];
                })
            ];
        })->groupBy('status');

        return [
            'data' => $orders,
            'message' => __('messages.reception.radiology.patients.orderList'),
        ];
    }

    public function showPatientSkiagraphOrder($patient, $order)
    {
        if ($patient->id !== $order->patient_id) {
            throw new \Exception('this order is not for this patient', 404);
        }
        $order = $patient->skiagraph_Orders()->with('reports', 'skaigraph_small_service')->find($order->id);
        $order = [
            'id' => $order->id,
            'patient_name' => $order->skaigraph_patient->patient_user->full_name,

            'doctor_name' => $order->doctor_name,
            'price' => $order->price,
            'status' => $order->status,
            'radiology_image_name' => $order->skaigraph_small_service->{'name_' . app()->getLocale()},
            'radiology_image_description' => $order->skaigraph_small_service->{'description_' . app()->getLocale()},
            'reports' => $order->reports->map(function ($item) {
                return [
                    'id' => $item->id,
                    'file_path' => $item->file_path,
                ];
            })
        ];
        return [
            'data' => $order,
            'message' => __('messages.reception.radiology.patients.orderList'),
        ];

    }

    public function createPatientSkiagraphOrder($patient, $request)
    {
        $section = Section::where('section_type', SectionType::Radiography)->first();
        $small_service = SmallService::where('id', $request->small_service_id)->first();

        if ($small_service->section_id !== $section->id) {
            throw new \Exception(__('messages.reception.radiology.services.not'), 404);
        }
        $data = DB::transaction(function () use ($request, $patient, $small_service) {

            $order = $patient->skiagraph_Orders()->create([
                'doctor_name' => $request->get('doctor_name'),
                'status' => SkiagraphOrderStatus::InPreparation,
                'small_service_id' => $small_service->id,
//        'doctor_id'=>1,
                'price' => $small_service->price,
            ]);
            return $order;
        });
        return [
            'data' => $data,
            'message' => __('messages.reception.radiology.patients.created'),
        ];

    }

    public function updatePatientSkiagraphOrder($patient, $order, $request)
    {
        if ((int)$patient->id !== (int)$order->patient_id) {
            throw new \Exception(__('ماعندي ولا طلب'), 422);
        }

        $data = DB::transaction(function () use ($request, $patient, $order) {

            // تحديث الحالة إذا انبعتت
            if ($request->filled('status')) {
                $order->update([
                    'status' => SkiagraphOrderStatus::tryFrom($request->get('status')) ?? $order->status,
                ]);
            }

            // حذف تقرير واحد
            if ($request->filled('deleted_report')) {
                $reportId = (int)$request->input('deleted_report');
                $report = $order->reports()->where('id', $reportId)->first();

                if (!$report) {
                    throw new \Exception(
                        __('messages.reception.radiology.services.not', ['id' => $reportId]),
                        422
                    );
                }

                if ($report->file_path) {
                    Storage::disk('public')->delete($report->file_path);
                }

                $report->delete();
            }

            // رفع تقرير جديد
            if ($request->hasFile('report')) {
                $path = $this->storeeReportFile($request->file('report'), $order->id);
                $order->reports()->create(['file_path' => $path]);

                $order->update(['status' => SkiagraphOrderStatus::Prepared]);
                event(new WhatsAppAnalysesPatient($patient->patient_user, $order, 2));
            }

            return $order->fresh()->load('reports');
        });

        return [
            'data' => $data,
            'message' => __('messages.reception.radiology.patients.updated'),
        ];
    }

    public function deletePatientSkiagraphOrder($patient, $order): array
    {
        // تأكيد الانتماء
        if ((int)$patient->id !== (int)$order->patient_id) {
            throw new \Exception(__('messages.reception.radiology.services.not'), 404);
        }

//        // (اختياري) اسمح بالحذف فقط بحالات معيّنة
//        if ($order->status !== SkiagraphOrderStatus::InPreparation) {
//            throw new \Exception(__('messages.reception.radiology.cannot_delete_non_preparation'), 409);
//        }

        DB::transaction(function () use ($order) {
            // احذف ملفات التقارير من الستوريج
            $paths = $order->reports()->pluck('file_path')->filter()->all();
            if (!empty($paths)) {
                Storage::disk('public')->delete($paths);
            }

            // احذف سجلات التقارير
            $order->reports()->delete();

            // احذف الطلب نفسه
            $order->delete(); // أو forceDelete() إذا ما بدك SoftDeletes
        });

        return [
            'data' => [],
            'message' => __('messages.reception.radiology.patients.deleted'),
        ];
    }
    
    public function storeeReportFile($file, $orderId)
    {
        // اسم أساس نظيف
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'report';
        $ext = $file->getClientOriginalExtension();


        $dir = "radiology/{$orderId}";
        $filename = "{$base}.{$ext}";
        $path = "{$dir}/{$filename}";
        $i = 1;
        while (Storage::disk('public')->exists($path)) {
            $filename = "{$base}-{$i}.{$ext}";
            $path = "{$dir}/{$filename}";
            $i++;
        }

        $storedPath = $file->storeAs($dir, $filename, 'public');

        return $storedPath;
    }

    // -------------------- radiology --------------------


}
