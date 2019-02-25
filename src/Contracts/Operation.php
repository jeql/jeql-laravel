<?php

namespace Jeql\Contracts;

use Jeql\InputSpecification;
use Jeql\OutputSpecification;
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
     * @return array|InputSpecification
     */
    public function expects();

    /**
     * @return array|OutputSpecification
     */
    public function outputs();
}