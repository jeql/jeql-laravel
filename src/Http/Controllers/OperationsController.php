<?php

namespace Jeql\Http\Controllers;

use Jeql\Contracts\Definition;
use Jeql\Contracts\Operation;
use Jeql\JeqlValidator;
use Jeql\OperationRegistry;
use Jeql\Request;

class OperationsController
{
    /**
     * @param OperationRegistry $operations
     * @param string $operation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(OperationRegistry $operations, string $route): \Illuminate\Http\JsonResponse
    {
        /** @var Definition|Operation $operation */
        $operation = $operations->match($route);

        (new JeqlValidator)->validate($operation, new Request);

        return $operation->handle();
    }
}