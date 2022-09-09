<?php

namespace App\Exceptions;

use App\Helpers\Telegram;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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

    public function report(Throwable $e)
    {
       $data = [
           'description' => $e->getMessage(),
           'file' => $e->getFile(),
           'line' => $e->getLine(),
           'route' => request()->url() ? request()->url() : '',
           'headers' => request()->headers->all(),
       ];
        Log::debug($e);
//
       $telegram = new Telegram(config('bots.bot'));
       $telegram->sendMessage(config('bots.report_id'), (string)view('report',$data));

    }


    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
//    protected function invalidJson($request, ValidationException $exception)
//    {
//        $message = "";
//        $errors = [];
//
//        foreach ($exception->errors() as $key => $error) {
//            $message = $error[0];
//            $errors[$key] = $error[0];
//        }
//
//        return response()->json([
//            'ok' => false,
//            'message' => $message,
//            'errors' => $errors
//        ], 200);
//    }
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception|Throwable $e)
    {

        if($e instanceof NotFoundHttpException)
        {
            if($request->hasCookie('language')) {
                $cookie = $request->cookie('language');
                app()->setLocale($cookie);
                //.... etc
            }
        }

        return parent::render($request, $e);
    }


}
