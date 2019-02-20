<?php

namespace Jeql\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException as IlluminateValidationException;

class Handler
{
    /** @var \Illuminate\Contracts\Debug\ExceptionHandler */
    protected $originalExceptionHandler;

    /** @var \App\Exceptions\JeqlExceptionFormatter */
    protected $formatter;

    /**
     * @param \Illuminate\Contracts\Debug\ExceptionHandler $originalExceptionHandler
     * @param \Jeql\Exceptions\Formatter $formatter
     */
    public function __construct(ExceptionHandler $originalExceptionHandler, Formatter $formatter)
    {
        $this->originalExceptionHandler = $originalExceptionHandler;
        $this->formatter = $formatter;
    }

    /**
     * Report or log an exception.
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function report(\Exception $e)
    {
        $this->originalExceptionHandler->report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, \Exception $exception)
    {
        if ($exception instanceof IlluminateValidationException) {
            $exception = new ValidationException($exception->validator, $exception);
        }

        if (!$exception instanceof SafeException) {
            $exception = new UnknownAppException($exception);
        }

        $data = [
            'error' => $this->formatter->format($exception, (bool)env('APP_DEBUG')),
        ];

        $response = new JsonResponse($data, JsonResponse::HTTP_OK, [], JSON_PARTIAL_OUTPUT_ON_ERROR);

        $response->withException($exception);

        return $response;
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     *
     * @return void
     */
    public function renderForConsole($output, \Exception $e)
    {
        $this->originalExceptionHandler->renderForConsole($output, $e);
    }
}
