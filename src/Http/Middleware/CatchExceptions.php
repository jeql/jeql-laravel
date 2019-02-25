<?php

namespace Jeql\Http\Middleware;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Jeql\Exceptions\Handler;
use Jeql\Exceptions\Formatter;

class CatchExceptions
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $originalExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->singleton(ExceptionHandler::class, function() use ($originalExceptionHandler) {
            $formatter = $this->app->make(Formatter::class);

            return new Handler($originalExceptionHandler, $formatter);
        });

        return $next($request);
    }
}
