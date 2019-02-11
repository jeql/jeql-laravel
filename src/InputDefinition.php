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

    /** @var array */
    protected static $__definitions = [];

    /**
     * @return array
     */
    abstract protected function expects(): array;

    /**
     * @param string $classname
     *
     * @return Definition
     * @throws \Exception
     */
    public static function instantiate(string $classname): Definition
    {
        // See if already instantiate once
        if ($instance = static::$__definitions[$classname] ?? '') {
            return $instance;
        }

        $instance = new $classname;

        // Make sure instance is instance of Definition
        if (!$instance instanceof Definition) {
            throw new \Exception("{$classname} is not an instance of InputDefinition");
        }

        // Store instance for reusability
        static::$__definitions[$classname] = $instance;

        return $instance;
    }

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