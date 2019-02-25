<?php

namespace Jeql;

use Jeql\Http\Controllers\BatchController;
use Jeql\Http\Controllers\IntrospectController;
use Jeql\Http\Controllers\OperationsController;
use Jeql\Http\Middleware\CatchExceptions;
use Laravel\Lumen\Routing\Router;

class Jeql
{
    /** @var string */
    protected static $operationsPath;

    /**
     * Register the typical JEQL routes for an application.
     *
     * @param \Laravel\Lumen\Routing\Router $router
     * @param array $groupOptions
     *
     * @return void
     */
    public static function routes(Router $router, array $groupOptions = [])
    {
        // Add the "catch exceptions" middleware at the start of the group's
        // middleware to ensure that all exceptions thrown within the group are
        // formatted correctly.
        if (isset($groupOptions['middleware'])) {
            array_unshift($groupOptions['middleware'], CatchExceptions::class);
        }

        $groupOptions['namespace'] = '\\';

        $router->group($groupOptions, function ($router) {
            // Route to handle batch calls
            $router->post('_batch', BatchController::class . '@handle');

            // Route to retrieve introspect
            $router->post('_introspect', IntrospectController::class . '@handle');

            // Route to handle all operations
            $router->post('{operation}', OperationsController::class . '@handle');
        });
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     */
    public static function loadOperationsFrom(string $path)
    {
        static::$operationsPath = $path;
    }

    /**
     * @return string
     */
    public static function getOperationsPath(): string
    {
        return static::$operationsPath ?: base_path('routes/jeql.php');
    }
}