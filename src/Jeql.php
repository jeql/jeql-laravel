<?php

namespace Jeql;

use Jeql\Http\Controllers\BatchController;
use Jeql\Http\Controllers\IntrospectController;
use Jeql\Http\Controllers\OperationsController;
use Illuminate\Routing\Router;

class Jeql
{
    /** @var string */
    protected static $operationsPath;

    /**
     * Register the typical JEQL routes for an application.
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->group(['namespace' => null], function ($router) {
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
        if (!file_exists($path)) {
            throw new \Exception("Could not find operations for path: {$path}");
        }

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