<?php

namespace App\Http\Controllers\Admin\Comments;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CommentResource;
use App\Models\Comment;
use App\Services\Admin\Comments\CommentsService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentsService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $filters = [
            'type' => $request->get('type', 'all'),
            'status' => $request->get('status', 'all'),
            'search' => $request->get('search', ''),
            'doctor_id' => $request->get('doctor_id'), // إضافة فلتر الدكتور المحدد
        ];

        // تحويل all إلى null للاستعلام
        if ($filters['status'] === 'all') {
            $filters['status'] = null;
        }

        // إذا تم تحديد doctor_id، نضبط type تلقائياً إلى doctors
        if (!empty($filters['doctor_id'])) {
            $filters['type'] = 'doctors';
        }

        $comments = $this->commentService->getAllComments($filters);
        $stats = $this->commentService->getCommentsStats();
        $comments->getCollection()->transform(function ($comment) {
            return new CommentResource($comment);
        });
        $data = [
            'comments' => $comments,
            'stats' => $stats,
            'filters' => $filters, // إرجاع الفلاتر المستخدمة
        ];

        return ApiResponse::success($data, 'تم جلب التعليقات بنجاح', 200);
    }
    public function show(Comment $comment)
    {
        try {
            $comment1 = $this->commentService->getCommentById($comment);

            return ApiResponse::success($comment1,'تمت العملية بنجاح',200);
        } catch (\Exception $e) {
            ApiResponse::error([],$e->getMessage(),400);
        }
    }

    public function toggle(Comment $comment)
    {
        try {
            $this->commentService->toggleComment($comment);

            return ApiResponse::success($comment,'تمت العملية بنجاح',200);

        } catch (\Exception $e) {
            return redirect()->route('admin.comments.index')
                ->with('error', 'حدث خطأ أثناء قبول التعليق: ' . $e->getMessage());
        }
    }


    public function destroy(Comment $comment)
    {
        try {
            $this->commentService->deleteComment($comment);

            return ApiResponse::success($comment,'تمت العملية بنجاح',200);

        } catch (\Exception $e) {
            return redirect()->route('admin.comments.index')
                ->with('error', 'حدث خطأ أثناء حذف التعليق: ' . $e->getMessage());
        }
    }
}
