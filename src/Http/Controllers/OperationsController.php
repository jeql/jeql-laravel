<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Jeql\OperationRegistry;

class OperationsController
{
    /**
     * @param OperationRegistry $operations
     * @param HttpRequest $request
     * @param string $operation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(OperationRegistry $operations, HttpRequest $request, string $route): \Illuminate\Http\JsonResponse
    {
        $operation = $operations->match($route);

        dd($operation);
    }
}