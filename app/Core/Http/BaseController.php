<?php

namespace LarAPI\Core\Http;

use Throwable;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends LaravelController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Builds and sends a simple success API response
     *
     * @param int $code
     * @return JsonResponse
     */
    protected function apiSimpleSuccessResponse(int $code = Response::HTTP_CREATED): JsonResponse
    {
        return response()->json(['success' => true], $code);
    }

    /**
     * Builds and sends a success API response
     *
     * @param mixed    $data
     * @param int|null $code
     * @return JsonResponse
     */
    protected function apiSuccessResponse($data, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $code);
    }

    /**
     * Builds and sends an error API response
     *
     * @param string         $message
     * @param Throwable|null $exception
     * @param int            $code
     * @return JsonResponse
     */
    protected function apiErrorResponse(
        string $message,
        Throwable $exception = null,
        $code = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $response = ['message' => $message];

        if (config('app.debug') && !empty($exception)) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTrace()
            ];
        }

        return response()->json($response, $code);
    }
}
