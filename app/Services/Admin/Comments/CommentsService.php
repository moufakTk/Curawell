<?php

namespace App\Services\Admin\Comments;

use App\Models\Comment;
use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

class CommentsService
{
    public function getAllComments($filters = [], $perPage = 15)
    {
        $query = Comment::with(['comment_patient', 'commentable']);

        if (!empty($filters['type'])) {
            switch ($filters['type']) {
                case 'doctors':
                    $query->where('commentable_type', Doctor::class);

                    if (!empty($filters['doctor_id'])) {
                        $query->where('commentable_id', $filters['doctor_id']);
                    }
                    break;
                case 'sections':
                    $query->where('commentable_type', Section::class);
                    break;
                case 'general':
                    $query->whereNull('commentable_type');
                    break;
            }
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where('comment', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
    public function getCommentById($comment)
    {
        return $comment->with(['comment_patient', 'commentable']);

    }

    public function toggleComment($comment)
    {
        try {
            $comment->update(['status' => !$comment->status]);

            return $comment;
        } catch (\Exception $e) {
            Log::error('Error approving comment: ' . $e->getMessage());
            throw new \Exception('Failed to approve comment');
        }
    }


    public function deleteComment($comment)
    {
        try {
            $comment->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting comment: ' . $e->getMessage());
            throw new \Exception('Failed to delete comment');
        }
    }

    public function getCommentsStats()
    {
        return [
            'total' => Comment::count(),
            'approved' => Comment::where('status', 1)->count(),
            'pending' => Comment::where('status', 0)->count(),
            'doctors' => Comment::where('commentable_type', Doctor::class)->count(),
            'sections' => Comment::where('commentable_type', Section::class)->count(),
            'general' => Comment::whereNull('commentable_type')->count(),
        ];
    }
}
