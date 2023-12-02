<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Traits\ApiResponse;
use Exception;

class Handler extends ExceptionHandler
{
    use ApiResponse;


    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return false;
        });
        $this->renderable(function (Exception $exception, $request) {
            return $this->handleException($request, $exception);
        });
    }

    public function handleException($request, Exception $exception): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {

        if ($exception instanceof MethodNotAllowedHttpException) {
            Log::error($exception->getMessage(), [
                'url' => $request->fullUrl(),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()]);
            return $this->errorResponse(__('api.method_not_allowed'), 405, '405');
        }
        if ($exception instanceof NotFoundHttpException) {
            Log::error($exception->getMessage(), [
                'url' => $request->fullUrl(),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()]);
            return $this->errorResponse(__('api.not_found'), 404, '404');
        }
        if ($exception instanceof HttpException) {
            Log::error($exception->getMessage(), [
                'url' => $request->fullUrl(),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()]);
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }
        if ($exception instanceof QueryException) {

            Log::error($exception->getMessage(), [
                'url' => $request->fullUrl(),
                'code' => $exception->getCode(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage()]);
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse(__('api.server_error'), 500, '500');
            }
            return $this->errorResponse($exception->getMessage(), 500, '500');

        }

        if ($exception instanceof \ErrorException) {
            Log::error($exception->getMessage(), [
                'url' => $request->fullUrl(),
                'code' => $exception->getCode(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage()]);
            return $this->errorResponse(__('api.server_error'), 500, '500');
        }
        if (config('app.debug')) {
            try {
                return parent::render($request, $exception);
            } catch (Throwable $e) {
                Log::error($exception->getMessage(), [
                    'url' => $request->fullUrl(),
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()]);
                return $this->errorResponse($exception->getMessage(), 500, '500');
            }
        }
        Log::error($exception->getMessage(), [
            'url' => $request->fullUrl(),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()]);
        return $this->errorResponse($exception->getMessage(), 500, '500');

    }
}

