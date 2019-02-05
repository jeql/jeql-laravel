<?php

namespace Jeql\Contracts;

use Jeql\Bags\ArgumentBag;

interface HasArguments
{
    /**
     * @return ArgumentBag
     */
    public function getArguments();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArgument(string $key);
}