<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = 'تمت العملية بنجاح', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($data = null,$message = 'حدث خطأ ما' , $status = 400,)
    {
        if ($status < 100 || $status > 599) {
            $status = 500;
        }
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
