<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;

class BatchController
{
    /**
     * @param HttpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(HttpRequest $request): \Illuminate\Http\JsonResponse
    {
        // @todo create batch handling

        return response()->json();
    }
}