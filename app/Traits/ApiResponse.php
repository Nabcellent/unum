<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = [], $msg = null, $code = 200): JsonResponse
    {
        $res['status'] = true;

        if ($data) {
            $res['data'] = $data;
        }
        if ($msg) {
            $res['msg'] = $msg;
        }

        return response()->json($res, $code);
    }

    protected function errorResponse($msg = null, $errors = null, $code = 500): JsonResponse
    {
        $res['status'] = false;

        if ($msg) {
            $res['msg'] = $msg;
        }
        if ($errors) {
            $res['errors'] = $errors;
        }

        return response()->json($res, $code);
    }
}
