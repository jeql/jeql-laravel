<?php

namespace Jeql\Contracts;

use Jeql\InputDefinition;
use Jeql\OutputDefinition;

interface Operation
{
    /**
     * Handle operation request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): \Illuminate\Http\JsonResponse;

    /**
     * @return array|InputDefinition
     */
    public function expects();

    /**
     * @return array|OutputDefinition
     */
    public function outputs();
}