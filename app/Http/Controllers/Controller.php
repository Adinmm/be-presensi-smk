<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function sendSuccessResponse($message = 'Success', $data = null, $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function sendErrorResponse($message = 'Error', $statusCode = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }
}