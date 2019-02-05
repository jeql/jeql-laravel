<?php

namespace Jeql;

use \Jeql\Contracts\Operation as OperationContract;

abstract class Operation implements OperationContract
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    abstract protected function resolve(): \Illuminate\Http\JsonResponse;

    /**
     * Handle operation request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): \Illuminate\Http\JsonResponse
    {
        $this->validateRequest();

        return $this->resolve();
    }

    /**
     * Validate the request based on operation definitions
     *
     * @return void
     * @throws \Exception
     */
    protected function validateRequest()
    {
        return;
    }
}