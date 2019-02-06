<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Jeql\Contracts\Definition;
use Jeql\Contracts\Operation;
use Jeql\JeqlValidator;
use Jeql\OperationRegistry;
use Jeql\Request;

class OperationsController
{
    /**
     * @param OperationRegistry $operations
     * @param HttpRequest $httpRequest
     * @param string $route
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(OperationRegistry $operations, HttpRequest $httpRequest, string $route): \Illuminate\Http\JsonResponse
    {
        /** @var Definition|Operation $operation */
        $operation = $operations->match($route);

        // Create root request
        $request = new Request(
            'root',
            $httpRequest->json('arguments'),
            $httpRequest->json('arguments')
        );

        // Validate request
        (new JeqlValidator)
            ->validate($operation, $request);

        // Handle operation
        return $operation->handle($request);
    }
}