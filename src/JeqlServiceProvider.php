<?php

namespace Jeql;

use App\Http\Requests\FindCustomerRequest;
use Illuminate\Support\ServiceProvider;
use Jeql\Contracts\Request;

class JeqlServiceProvider extends ServiceProvider
{
    /**
     * Register JEQL's services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('jeql.type', function () {
            return new TypeRegistry;
        });

        // Register OperationRegistry
        $this->app->singleton(OperationRegistry::class);
        $this->app->resolving(OperationRegistry::class, function (OperationRegistry $operations) {
            $path = Jeql::getOperationsPath();

            if (!file_exists($path)) {
                throw new \Exception("Could not find operations for path: {$path}");
            }

            require_once($path);
        });
    }

    /**
     * Boot method
     *
     * @return void
     */
    public function boot()
    {
    }
}
