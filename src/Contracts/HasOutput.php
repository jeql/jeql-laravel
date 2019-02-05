<?php

namespace Jeql\Contracts;

use Jeql\Bags\OutputBag;
use Jeql\OutputDefinition;

interface HasOutput
{
    /**
     * @return OutputBag
     */
    public function getFields();

    /**
     * @param string $key
     *
     * @return ScalarType|OutputDefinition|null
     */
    public function getField(string $key);
}