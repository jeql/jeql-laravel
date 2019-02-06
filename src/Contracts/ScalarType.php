<?php

namespace Jeql\Contracts;

interface ScalarType
{
    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return void
     */
    public function validate(string $attribute, $value);

    /**
     * @return array
     */
    public function getRules(): array;
}