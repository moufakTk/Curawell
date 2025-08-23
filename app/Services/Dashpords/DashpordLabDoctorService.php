<?php

namespace App\Services\Dashpords;

use App\Enums\Orders\AnalyzeOrderStatus;
use App\Events\WhatsAppAnalysesPatient;
use App\Http\Resources\Analyze\AnalyzeOrderResource;
use App\Models\AnalyzeOrder;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Laravel\Prompts\select;


class DashpordLabDoctorService
{


        public function patientAnalyses($patient)
        {

            $analyses = AnalyzeOrder::where("patient_id",$patient->id,)
                ->with([
                    'analyzed_order_patient.patient_user',
                    'AnalyzeRelated.analyzesRelated_analyze',
                    'samplesRelated.SamplesRelated_sample',
                    'reports'
                ])->get();
            $analyses =AnalyzeOrderResource::collection($analyses);
            $analyses=$analyses->groupBy('status');
            return [
                'data'=>$analyses,
                'message'=>('messages.reception.analyze_orders.list'),
            ];

        }





public function pendingAnalyses(){

    $analyses = AnalyzeOrder::where('status', AnalyzeOrderStatus::Pending)
        ->with([
            'analyzed_order_patient.patient_user',      // لاسم المريض
            'AnalyzeRelated.analyzesRelated_analyze',   // عناصر التحاليل
            'samplesRelated.SamplesRelated_sample',
            'reports'// العينات
        ])->get();
    return [
        'data'=>AnalyzeOrderResource::collection($analyses)
        ,'message'=>__('messages.reception.analyze_orders.list'),
    ];

}
//    public function patientAnalyses($patient){
//
//        $analyses = AnalyzeOrder::where("patient_id",$patient->id,)
//            ->with([
//                'analyzed_order_patient.patient_user',
//                'AnalyzeRelated.analyzesRelated_analyze',
//                'samplesRelated.SamplesRelated_sample',
//                'reports'
//            ])->get();
//        $analyses =AnalyzeOrderResource::collection($analyses);
//        $analyses=$analyses->groupBy('status');
//        return [
//            'data'=>$analyses,
//            'message'=>__('messages.reception.analyze_orders.list'),
//        ];
//
//    }

    public function Analyses(){

    $user = auth()->user();
        $analyses = AnalyzeOrder::where('Doctor_id',$user->doctor->id)->where('status','!=', AnalyzeOrderStatus::Completed)
            ->with([
                'analyzed_order_patient.patient_user',      // لاسم المريض
                'AnalyzeRelated.analyzesRelated_analyze',   // عناصر التحاليل
                'samplesRelated.SamplesRelated_sample',
                'reports'// العينات
            ])->get();
        $analyses = AnalyzeOrderResource::collection($analyses);
        $analyses = $analyses->groupBy('status');
        return [
            'data'=>$analyses,
            'message'=>__('messages.reception.analyze_orders.list'),
        ];

    }

    public function completedAnalyses(){

        $user = auth()->user();
        $analyses = AnalyzeOrder::where('Doctor_id',$user->doctor->id)->where('status','=', AnalyzeOrderStatus::Completed)
            ->with([
                'analyzed_order_patient.patient_user',      // لاسم المريض
                'AnalyzeRelated.analyzesRelated_analyze',   // عناصر التحاليل
                'samplesRelated.SamplesRelated_sample',
                'reports'// العينات
            ])->get();
        $analyses = AnalyzeOrderResource::collection($analyses);
        return [
            'data'=>$analyses,
            'message'=>__('messages.reception.analyze_orders.list'),
        ];

    }
    public function countAnalyses()
    {
        $user = auth()->user();

        $analyses = AnalyzeOrder::where('doctor_id', $user->doctor->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'data'    => $analyses,
            'message' => __('messages.reception.analyze_orders.list'),
        ];
    }

    public function updateAnalyses($analyzeOrder, $request)
    {
        $user = auth()->user();

        $data = DB::transaction(function () use ($analyzeOrder, $request, $user) {
            // حوّل الستاتس لو وصل كنص للـ Enum، وإلا خليه الحالي
            $status = $request->filled('status')
                ? (AnalyzeOrderStatus::tryFrom($request->status) ?? $analyzeOrder->status)
                : $analyzeOrder->status;

            if ($status === AnalyzeOrderStatus::Accepted) {
                $analyzeOrder->update([
                    'status'      => AnalyzeOrderStatus::Accepted,
                    'doctor_id'   => $user->doctor->id ?? $analyzeOrder->doctor_id,
                    'doctor_name' => $user->full_name,
                ]);
            } elseif ($status === AnalyzeOrderStatus::InProgress) {
                $analyzeOrder->update(['status' => $status]);
            }


            $toDeleteIds = (array) $request->input('deleted_reports', []);
            if (!empty($toDeleteIds)) {
                $reports = $analyzeOrder->reports()->whereIn('id', $toDeleteIds)->get();
                foreach ($reports as $rep) {
                    if ($rep->file_path) {
                        Storage::disk('public')->delete($rep->file_path);
                    }
                    $rep->delete();
                }
                $foundIds = $reports->pluck('id')->all();
                $missing  = array_diff($toDeleteIds, $foundIds);
                if (!empty($missing)) {
                    throw new \Exception(__('messages.DoctorLab.analyze_orders.deleted_missing_reports'),422);
                }
            }

            // رفع تقارير متعددة
            if ($request->hasFile('reports')) {
                $files = $request->file('reports'); // array of UploadedFile

                foreach ($files as $file) {
                    $storedPath = $this->storeAnalysisReportFile($file, $analyzeOrder->id);

                    $analyzeOrder->reports()->create([
                        'file_path' => $storedPath,
                    ]);
                }

                // بعد رفع التقارير حدث حالة الطلب (حسب ما كاتب)
                $analyzeOrder->update([
                    'status' => AnalyzeOrderStatus::Completed,
                ]);
                event(new WhatsAppAnalysesPatient($analyzeOrder->analyzed_order_patient->patient_user,$analyzeOrder,1));
            }

            return [
                'data'    => $analyzeOrder->fresh()->load('reports'),
                'message' => __('messages.DoctorLab.analyze_orders.status'),
            ];
        });

        return $data;
    }

    public function storeAnalysisReportFile($file, $orderId)
    {
        // اسم أساس نظيف
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'report';
        $ext  = $file->getClientOriginalExtension();


        $dir = "analyses/{$orderId}";
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

}
