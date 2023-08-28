<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = [], $message = null, $code = 200): JsonResponse
    {
        $res['status'] = true;

        if ($data) {
            $res['data'] = $data;
        }
        if ($message) {
            $res['message'] = $message;
        }

        return response()->json($res, $code);
    }

    protected function errorResponse($message = null, $errors = null, $code = 500): JsonResponse
    {
        $res['status'] = false;

        if ($message) {
            $res['message'] = $message;
        }
        if ($errors) {
            $res['errors'] = $errors;
        }

        return response()->json($res, $code);
    }
}
