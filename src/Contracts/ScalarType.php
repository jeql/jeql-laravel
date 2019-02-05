<?php

namespace Jeql\Contracts;

interface ScalarType
{
    /**
     * @param mixed $value
     *
     * @return void
     * @throws \Exception
     */
    public function validate($value);

    /**
     * @return array
     */
    public function getRules(): array;
}