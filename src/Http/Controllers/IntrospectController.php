<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;

class IntrospectController
{
    /**
     * @param HttpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        // @todo create introspect handling

        return response()->json();
    }
}