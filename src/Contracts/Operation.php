<?php

namespace Jeql\Contracts;

use Jeql\InputDefinition;
use Jeql\OutputDefinition;
use Jeql\Request;

interface Operation
{
    /**
     * Handle operation request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request): \Illuminate\Http\JsonResponse;

    /**
     * @return array|InputDefinition
     */
    public function expects();

    /**
     * @return array|OutputDefinition
     */
    public function outputs();
}