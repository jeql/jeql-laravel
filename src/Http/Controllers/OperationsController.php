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
     * @param HttpRequest $request
     * @param string $route
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(OperationRegistry $operations, HttpRequest $request, string $route): \Illuminate\Http\JsonResponse
    {
        /** @var Definition|Operation $operation */
        $operation = $operations->match($route);

        (new JeqlValidator)
            ->validate($operation, new Request(
                'root',
                $request->json('arguments'),
                $request->json('arguments')
            ));

        return $operation->handle();
    }
}