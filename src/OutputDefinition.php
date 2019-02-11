<?php

namespace Jeql;

use Jeql\Bags\DefinitionBag;
use Jeql\Contracts\Argument;
use Jeql\Contracts\Definition;
use Jeql\Contracts\Field;
use Jeql\Contracts\HasInputDefinitions;
use Jeql\Contracts\HasOutputDefinitions;
use Jeql\Contracts\ScalarType;

abstract class OutputDefinition implements Definition, HasInputDefinitions, HasOutputDefinitions
{
    /** @var null|DefinitionBag */
    protected $inputDefinitions;

    /** @var null|DefinitionBag */
    protected $outputDefinitions;

    /** @var array */
    protected static $__definitions = [];

    /**
     * @return array
     */
    abstract protected function expects(): array;

    /**
     * @return array
     */
    abstract protected function outputs(): array;

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
            throw new \Exception("{$classname} is not an instance of OutputDefinition");
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

    /**
     * @param string $key
     *
     * @return ScalarType|OutputDefinition|mixed|null
     */
    public function getOutput(string $key)
    {
        return $this->getOutputDefinitions()->get($key);
    }

    /**
     * @return DefinitionBag
     */
    public function getOutputDefinitions(): DefinitionBag
    {
        if (!$this->outputDefinitions) {
            $this->outputDefinitions = new DefinitionBag($this->outputs());
        }

        return $this->outputDefinitions;
    }
}