<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Helpers\Api;
use Illuminate\Database\LazyLoadingViolationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json(['succes' => false, 'code' => 404, 'error' => 'Not Found!'], 404);
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {          

            $guards = $exception->guards();

            if (in_array('sanctum', $guards)) {
                return Api::errorResponse(401, 'Invalid token');                
            }
        }
        if ($exception instanceof LazyLoadingViolationException) {
            return Api::errorResponse(500, 'Lazy Loading: '.$exception->getMessage()); 
        }

        return parent::render($request, $exception);
    }
}
