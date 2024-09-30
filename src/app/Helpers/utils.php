<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof AbstractPaginator) {
            $pagination = [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ];

            $response['pagination'] = $pagination;
        }

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
