<?php

namespace Jeql;

use Jeql\Bags\DefinitionBag;
use Jeql\Contracts\Argument;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasInputDefinitions;
use Jeql\Contracts\ScalarType;

abstract class InputDefinition implements Definition, HasInputDefinitions
{
    /** @var null|DefinitionBag */
    protected $inputDefinitions;

    /**
     * @return array
     */
    abstract protected function expects(): array;

    /**
     * @param string $key
     *
     * @return ScalarType|InputDefinition|null
     */
    public function getInput(string $key)
    {
        return $this->getInputDefinitions()->get($key);
    }

    /**
     * @return DefinitionBag
     */
    public function getInputDefinitions(): DefinitionBag
    {
        if (!$this->inputDefinitions) {
            $this->inputDefinitions = new DefinitionBag($this->expects());
        }

        return $this->inputDefinitions;
    }
}