<?php

namespace Jeql\Contracts;

interface Operation
{
    /**
     * Handle operation request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): \Illuminate\Http\JsonResponse;

    /**
     * @return array|InputContract
     */
    public function arguments();

    /**
     * @return array|OutputContract
     */
    public function outputs();
}