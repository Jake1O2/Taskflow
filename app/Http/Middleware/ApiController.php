<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function sendSuccess(mixed $data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    protected function sendError(string $error, string $errorCode = 'error', int $statusCode = 400, ?array $data = null): JsonResponse
    {
        $response = [
            'success' => false,
            'error' => $error,
            'code' => $errorCode,
        ];

        if ($data) $response['data'] = $data;

        return response()->json($response, $statusCode);
    }
}
