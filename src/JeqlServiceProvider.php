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
        $this->app->bind('jeql.type', function() {
            return new Type;
        });

        $this->app->bind(Request::class);

        $this->app->resolving(Request::class, function(Request $request) {
            $request->validate();
        });
    }
}
