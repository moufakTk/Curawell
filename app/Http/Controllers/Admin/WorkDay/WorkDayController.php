<?php

// app/Http/Controllers/Admin/WorkDayController.php
namespace App\Http\Controllers\Admin\WorkDay;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\WorkDay;
use App\Services\Admin\WorkDay\WorkDayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkDayController extends Controller
{
    public function __construct(private WorkDayService $svc) {}

    // فتح فترة (افتراضي 45 يوم من اليوم)
    public function open(Request $req)
    {
        $data = $req->validate([
            'from'   => ['nullable','date'],
            'to'     => ['nullable','date','after_or_equal:from'],
            'days'   => ['nullable','integer','min:1','max:400'],
            'status' => ['nullable','integer','in:0,1'],
        ]);

        $status = $data['status'] ?? 1;

        // الحالة المطلوبة: days فقط → افتح من آخر تاريخ موجود + 1 أو من اليوم
        if (!isset($data['from']) && !isset($data['to']) && isset($data['days'])) {
            $res = $this->svc->openNextDays((int)$data['days'], $status);
            return ApiResponse::success($res, 'تم فتح أيام العمل من آخر تاريخ موجود');
        }

        // باقي الحالات: from/to أو from+days
        $from = isset($data['from']) ? Carbon::parse($data['from'])->startOfDay() : now()->startOfDay();
        $to   = isset($data['to'])
            ? Carbon::parse($data['to'])->startOfDay()
            : $from->copy()->addDays(($data['days'] ?? 45) - 1);

        $res = $this->svc->ensureRange($from, $to, $status);

        return ApiResponse::success([
            'from'    => $from->toDateString(),
            'to'      => $to->toDateString(),
            'created' => $res['created'],
            'skipped' => $res['skipped'],
        ], 'تم فتح أيام العمل بنجاح');
    }

    // استعراض ضمن مدى + فلترة حالة
    public function index(Request $req)
    {
        $data = $req->validate([
            'from'   => ['nullable','date'],
            'to'     => ['nullable','date','after_or_equal:from'],
            'status' => ['nullable','integer','in:0,1'],
            'per_page' => ['nullable','integer','min:1','max:200'],
        ]);

        $items = $this->svc->listRange($data['from'] ?? null, $data['to'] ?? null, $data['status'] ?? null);

        return ApiResponse::success($items);
    }

    // تلخيص الأيام المفتوحة من اليوم وطالع
    public function summary()
    {
        return ApiResponse::success([
            'open_future_days' => $this->svc->countOpenFutureDays(),
            'threshold' => 10,
            'auto_fill_days' => 45,
        ]);
    }

    // تبديل الحالة (دوام/عطلة)
    public function toggle(WorkDay $workDay)
    {
        $day = $this->svc->toggle($workDay);
        return ApiResponse::success([
            'id' => $day->id,
            'history' => $day->history,
            'status' => (int)$day->status
        ], 'تم تبديل حالة اليوم');
    }

    // ضبط الحالة بشكل صريح
    public function setStatus(Request $req, WorkDay $workDay)
    {
        $data = $req->validate([
            'status' => ['required','integer','in:0,1']
        ]);
        $day = $this->svc->setStatus($workDay, $data['status']);
        return ApiResponse::success([
            'id' => $day->id,
            'history' => $day->history,
            'status' => (int)$day->status
        ], 'تم تحديث حالة اليوم');
    }

    public function autoTopUp(Request $req)
    {
        $data = $req->validate([
            'days'      => ['nullable','integer','min:1','max:400'],   // كم بدك تفتح للأمام (افتراضي 45)
            'threshold' => ['nullable','integer','min:1','max:60'],   // لو الباقي أقل من هذا الرقم → كمّل تلقائياً (افتراضي 10)
        ]);

        $days = $data['days'] ?? 45;
        $th   = $data['threshold'] ?? 10;

        $res = $this->svc->autoTopUpIfNeeded($days, $th);

        if ($res === null) {
            return ApiResponse::success([
                'open_future_days' => $this->svc->countOpenFutureDays(),
                'message' => 'لا حاجة للتعبئة—عدد الأيام المفتوحة كافٍ',
            ]);
        }

        return ApiResponse::success($res, 'تمت التعبئة التلقائية بنجاح');
    }
}
