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

        $this->registerOperationRegistry();
    }

    /**
     * Boot method
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Bind the operation registry to the app container.
     */
    protected function registerOperationRegistry()
    {
        $this->app->singleton(OperationRegistry::class);

        $this->app->resolving(OperationRegistry::class, function (OperationRegistry $operations) {
            $path = Jeql::getOperationsPath();

            if (!file_exists($path)) {
                throw new \RuntimeException("Operation routes file not found at {$path}");
            }

            require_once($path);
        });
    }
}
