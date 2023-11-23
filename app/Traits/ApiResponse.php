<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data, $message = 'Resource trouvé', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'hasPagination' => false,
            'data' => $data
        ], $code);
    }

    protected function successResponseWithPagination($data, $message = 'Resource trouvé', $code = 200): \Illuminate\Http\JsonResponse
    {
        $links = [
            'first' => $data->url(1),
            'last' => $data->url($data->lastPage()),
            'prev' => $data->previousPageUrl(),
            'next' => $data->nextPageUrl(),
        ];
        $meta = [
            'current_page' => $data->currentPage(),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'path' => $data->path(),
            'per_page' => $data->perPage(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
        ];
        return response()->json([
            'success' => true,
            'message' => $message,
            'hasPagination' => true,
            'data' => $data,
            'links' => $links,
            'meta' => $meta,

        ], $code);
    }

    protected function errorResponse($message = ' Impossible d\'accéder à cette resource ', $errorCode = 1, $code = '400'): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => $errorCode,
            'message' => $message
        ], $code);
    }
}
