<?php

namespace Jeql\Contracts;

use Jeql\Bags\DefinitionBag;
use Jeql\OutputDefinition;

interface HasOutputDefinitions
{
    /**
     * @return DefinitionBag
     */
    public function getOutputDefinitions();

    /**
     * @param string $key
     *
     * @return ScalarType|OutputDefinition|null
     */
    public function getOutput(string $key);
}