<?php

namespace LarAPI\Core\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->error($exception, Response::HTTP_UNAUTHORIZED, 'Unauthenticated');
        }

        if ($exception instanceof ValidationException) {
            return $this->error($exception, Response::HTTP_UNPROCESSABLE_ENTITY, $exception->errors());
        }

        if (
            $exception instanceof ModelNotFoundException
            || $exception instanceof NotFoundHttpException
        ) {
            return $this->error($exception, Response::HTTP_NOT_FOUND, 'Resource not found');
        }

        if ($exception instanceof LarAPIExceptionInterface) {
            return $this->error($exception);
        }

        return $this->error($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->error($exception, Response::HTTP_UNAUTHORIZED, 'Unauthenticated');
    }

    /**
     * Builds an error response
     *
     * @param Throwable   $exception
     * @param int|null    $code
     * @param string|array|null $message
     * @return JsonResponse
     */
    private function error(Throwable $exception, int $code = null, $message = null): JsonResponse
    {
        $response = ['message' => $message ?: $exception->getMessage()];
        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'trace'   => $exception->getTrace()
            ];
        }

        return response()->json($response, $code ?: $exception->getCode());
    }
}
