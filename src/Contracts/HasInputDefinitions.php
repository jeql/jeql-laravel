<?php

namespace Jeql\Contracts;

use Jeql\Bags\DefinitionBag;
use Jeql\Bags\OutputBag;
use Jeql\InputDefinition;

interface HasInputDefinitions
{
    /**
     * @return DefinitionBag
     */
    public function getInputDefinitions(): DefinitionBag;

    /**
     * @param string $key
     *
     * @return ScalarType|InputDefinition|null
     */
    public function getInput(string $key);
}