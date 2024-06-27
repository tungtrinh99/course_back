<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseResponse
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(
        $data = null,
        $message = 'Operation successful',
        $status = Response::HTTP_OK
    ): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param mixed $errors
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(
        $message = 'Operation failed',
        $errors = null,
        $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $status);
    }
}
