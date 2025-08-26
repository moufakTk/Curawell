<?php

namespace App\Http\Controllers\Dashpords;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Enums\Orders\SkiagraphOrderStatus;
use App\Enums\ProcessTakeSample;
use App\Enums\SampleType;
use App\Enums\Services\SectionType;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Analyses\StoreAnalyzeOrderRequest;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Models\Analyze;
use App\Models\AnalyzeOrder;
use App\Models\Patient;
use App\Models\Sample;
use App\Models\Section;
use App\Models\SkiagraphOrder;
use App\Models\SmallService;
use App\Models\User;
use App\Services\Dashpords\DashpordReceptionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use mysql_xdevapi\Exception;
use phpseclib3\File\ASN1\Maps\Attribute;

class DashpordReceptionController extends Controller
{
    protected $dashpordReceptionService;

    public function __construct(DashpordReceptionService $dashpordReceptionService)
    {
        $this->dashpordReceptionService = $dashpordReceptionService;
    }

    public function registerPatient(Request $request)
    {
        $request->validate([

            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:male,female',
            'email' => 'required|string|email|max:100|unique:users',
//            'email' => 'required|string|email|max:100',
//            'password' => 'required|string|confirmed|min:8',
            'phone' => 'required|string|between:10,20|unique:users,phone',
            'address' => 'required|string',
//            'user_type'=>'required',


            // patient table
            'civil_id_number' => 'required|string|between:8,15|unique:patients,civil_id_number',
        ]);
        try {
            $data = $this->dashpordReceptionService->registerPatient($request);
            return ApiResponse::success($data, __('messages.reception_create_patient'), 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function searchPatient(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);
        try {
            $data = $this->dashpordReceptionService->searchPatient($request->search);
            return ApiResponse::success($data, __('messages.reception_search_patient'), 200);

        } catch (\Exception $exception) {
            return ApiResponse::error(null, $exception->getMessage(), $exception->getCode() ? 0 : 500);
        }

    }

    // -------------------- patient profile --------------------
    public function getSection()
    {
        $section_type = [SectionType::HomeCare, SectionType::Clinics];
        $sections = Section::whereIn('section_type', $section_type)->with(['services' => function ($query) {
            $query->where('section_id', section::where('section_type', SectionType::Clinics)->first()->id);

        }])->get();
        $sections = $sections->map(function ($section) {
            return [
                'id' => $section->id,
                'section_type' => $section->section_type,
                'services' => $section->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'section_id' => $service->section_id,
                        'name' => $service->{'name_' . App::getLocale()},
                    ];
                })
            ];
        });

        return ApiResponse::success($sections, 'تم عرض الداتا', 200);
    }

    public function patientInformation(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->patientInformation($patient);
            return ApiResponse::success($data, 'كل معلومات المريض هاد ' . $patient->patient_user->full_name, 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function all_app_Homecare(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->all_app_Homecare($patient);
            return ApiResponse::success($data, 'كل حجوزات المريض هاد ' . $patient->patient_user->full_name, 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function all_app_clinic(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->all_app_clinic($patient);
            return ApiResponse::success($data, 'كل حجوزات المريض هاد ' . $patient->patient_user->full_name, 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }
    public function patientReports(?Patient $patient = null)
    {
        // لو ما نُمرِّر مريض، منجيب مريض المستخدم الحالي
        if (is_null($patient)) {
            $user = auth()->user();
            if (!$user || !$user->patient) {
                throw new \Exception(__('messages.reception.radiology.patients.notPatient'), 403);
            }
            $patient = $user->patient;
        }

        // --- القسم الأول: التحاليل ---
        $analyzeOrders = $patient->analyze_orders()->where('status',AnalyzeOrderStatus::Completed)
            ->with([
                // أول ملف تقرير كرابط
                'reports',
                // اسم أول تحليل إذا ما كان عند الطلب اسم
                'AnalyzeRelated.analyzesRelated_analyze:id,name_ar,name_en,price',
            ])
            ->latest()
            ->get();

        $analyses = $analyzeOrders->map(function ($order) {
            // الاسم: إمّا اسم الطلب، وإلا اسم أول تحليل ضمنه
            $name = $order->name;
            if (!$name) {
                $firstAnalyze = optional($order->AnalyzeRelated->first())->analyzesRelated_analyze;
                $name = $firstAnalyze ? $firstAnalyze->{'name_' . app()->getLocale()} : null;
            }

            return [
                'id'    => $order->id,
                'name'  => $name,
                'date'  => optional($order->created_at)->format('Y-m-d'),
                'price' => $order->price,
                'link'  => optional($order->reports->first())->file_path, // إذا بدك URL مطلق: Storage::url(...)
            ];
        });

        // --- القسم الثاني: التصوير (سكياغراف) ---
        $skiagraphOrders = $patient->skiagraph_Orders()->where('status',SkiagraphOrderStatus::Prepared)
            ->with([
                'reports',
                'skaigraph_small_service:id,name_ar,name_en',
            ])
            ->latest()
            ->get();

        $skiagraph = $skiagraphOrders->map(function ($order) {
            return [
                'id'    => $order->id,
                'name'  => optional($order->skaigraph_small_service)->{'name_' . app()->getLocale()},
                'date'  => optional($order->created_at)->format('Y-m-d'),
                'price' => $order->price,
                'link'  => optional($order->reports->first())->file_path,
            ];
        });

        return [
            'data' => [
                'analyses'  => $analyses,
                'skiagraph' => $skiagraph,
            ],
            'message' => __('messages.reception.patients.reportsList'),
        ];
    }

    // -------------------- patient profile --------------------

    // -------------------- samples --------------------
    public function createSample(Request $request, Patient $patient)
    {
        $request->validate([
            'sample_type' => ['required', Rule::enum(SampleType::class)],
            'process_take' => ['required', Rule::enum(ProcessTakeSample::class)],
            'time_take' => 'nullable',
            'time_don' => 'nullable',
            'status' => 'nullable',
        ]);
        try {
            $request->route()->parameter('patient');
            $data = $this->dashpordReceptionService->createSample($request, $patient);
            return ApiResponse::success($data, __('messages.reception_create_sample'), 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function showSamplesType()
    {
        try {
            $data = $this->dashpordReceptionService->showSamplesType();
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }
    public function showSamples(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->showSamples($patient);
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }
    public function showPatientSamples(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->showPatientSamples($patient);
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }

    public function updateSample(Patient $patient, Sample $sample, Request $request)
    {
        $request->validate([
            'sample_type' => ['nullable', Rule::enum(SampleType::class)],
            'process_take' => ['nullable', Rule::enum(ProcessTakeSample::class)],
        ]);
        try {
            $data = $this->dashpordReceptionService->updateSample($request, $patient, $sample);
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() ?? 500);
        }

    }

    public function deleteSample(Patient $patient, Sample $sample)
    {
        try {
            $data = $this->dashpordReceptionService->deleteSample($patient, $sample);
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() ?? 500);
        }

    }

    // -------------------- samples --------------------

    // -------------------- analyses --------------------

    public function showPatientsAnalyses()
    {
        try {
            $data = $this->dashpordReceptionService->showPatientsAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function showPatientAnalyses(Patient $patient)
    {
        try {
            $data = $this->dashpordReceptionService->showPatientAnalyses($patient);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function createPatientAnalyse(Patient $patient, StoreAnalyzeOrderRequest $request)
    {
        try {
            $data = $this->dashpordReceptionService->createPatientAnalyse($request, $patient);

            return ApiResponse::success($data, __('messages.reception.analyze_orders.created'), 200);

        } catch (\Exception $exception) {
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() ?? 500);
        }
    }

    public function showPatientAnalyse(Patient $patient,AnalyzeOrder $order)

    {
        try {
            $data = $this->dashpordReceptionService->showPatientAnalyse($patient,$order);
            return ApiResponse::success($data, 'كل معلومات المريض هاد ' . $patient->patient_user->full_name, 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }


//    public function updatePatientAnalyse(){}
    public function deletePatientAnalyse(Patient $patient, AnalyzeOrder $order)
    {
        $this->dashpordReceptionService->deletePatientAnalyse($patient, $order);
        return ApiResponse::success(__('messages.reception.analyze_orders.deleted'), 200);

    }

    public function showAnalyses()
    {
        try {
            $data = $this->dashpordReceptionService->showAnalyses();
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }

    // -------------------- analyses --------------------

    // -------------------- radiology --------------------
    public function radiologyServices()
    {
        try {

            $data = $this->dashpordReceptionService->radiologyServices();
            return ApiResponse::success($data['data'], $data['message'], 200);

        }
        catch (\Exception $exception){
            return ApiResponse::error([], $exception->getMessage(), $exception->getCode() == 0 | 1 ? 500 : $exception->getCode());
        }
    }

    public function showRadiologyServices(SmallService $service)
    {
        try {

            $data = $this->dashpordReceptionService->showRadiologyservices($service);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function radiologyDoctors()
    {
        try {

            $data = $this->dashpordReceptionService->radiologyDoctors();
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }
    public function showPatientSkiagraphOrders(Patient $patient=null){
        try {

            $user = auth()->user();

            if ($patient) {
                if (!$user->hasRole('Reception')) {
                    throw new \Exception(__('messages.auth.unauthorized'), 403);
                }
            }

            else {

                if (!$user->hasRole('Patient') ) {
                    throw new \Exception(__('messages.auth.unauthorized'), 403);
                }
            }


            $data = $this->dashpordReceptionService->showPatientSkiagraphOrders($patient);
            return ApiResponse::success($data['data'], $data['message'], 200);

        }catch (\Exception $exception){
            return ApiResponse::error([],$exception->getMessage(),$exception->getCode() == 0 | null ? 500 : $exception->getCode());
        }

    }


    public function showSkiagraphOrders()
    {
        try {
            $data = $this->dashpordReceptionService->showSkiagraphOrders();
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }

    public function countSkiagraphOrders()
    {
        try {
            $data = $this->dashpordReceptionService->countSkiagraphOrders();
            return ApiResponse::success($data['data'], $data['message'], 200);
        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }
    }


    public function showPatientSkiagraphOrder(Patient $patient, SkiagraphOrder $order)
    {
        try {
            $data = $this->dashpordReceptionService->showPatientSkiagraphOrder($patient, $order);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }

    public function createPatientSkiagraphOrder(Patient $patient, Request $request)
    {
        $request->validate([
            'doctor_name' => ['nullable'],
            'small_service_id' => ['nullable'],
        ]);
        try {
            $data = $this->dashpordReceptionService->createPatientSkiagraphOrder($patient, $request);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);

        }

    }

    public function updatePatientSkiagraphOrder(Patient $patient, SkiagraphOrder $order, Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::enum(SkiagraphOrderStatus::class)],
            'report' => ['nullable', 'file', 'mimes:pdf,png,jpg', 'max:5120'],
            'delete_report' => ['nullable', Rule::exists('reports', 'id')],
        ]);
        try {
            $data = $this->dashpordReceptionService->updatePatientSkiagraphOrder($patient, $order, $request);
            return ApiResponse::success($data['data'], $data['message'], 200);

        }catch (\Exception $exception){
            $code = (int) $exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }

    public function deletePatientSkiagraphOrder(Patient $patient, SkiagraphOrder $order)
    {
        try {
            $data = $this->dashpordReceptionService->deletePatientSkiagraphOrder($patient, $order);
            return ApiResponse::success($data['data'], $data['message'], 200);

        } catch (\Exception $exception) {
            $code = (int)$exception->getCode();
            if ($code < 100 || $code > 599) {
                $code = 500;
            }

            return ApiResponse::error([], $exception->getMessage(), $code);
        }

    }


    // -------------------- radiology --------------------



}
