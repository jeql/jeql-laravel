<?php

namespace Jeql\Exceptions;

interface ContainsDetails
{
    /**
     * @return array
     */
    public function getDetails(): array;
}