<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\Argument;
use Jeql\Contracts\Specification;
use Jeql\Contracts\Field;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ScalarType;

abstract class OutputSpecification implements Specification, HasInputSpecifications, HasOutputSpecifications
{
    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /** @var null|SpecificationBag */
    protected $outputSpecifications;

    /** @var array */
    protected static $__specifications = [];

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
     * @return Specification
     * @throws \Exception
     */
    public static function instantiate(string $classname): Specification
    {
        // See if already instantiate once
        if ($instance = static::$__specifications[$classname] ?? '') {
            return $instance;
        }

        $instance = new $classname;

        // Make sure instance is instance of Specification
        if (!$instance instanceof Specification) {
            throw new \Exception("{$classname} is not an instance of OutputSpecification");
        }

        // Store instance for reusability
        static::$__specifications[$classname] = $instance;

        return $instance;
    }

    /**
     * @param string $key
     *
     * @return ScalarType|InputSpecification|null
     */
    public function getInput(string $key)
    {
        return $this->getInputSpecifications()->get($key);
    }

    /**
     * @return SpecificationBag
     */
    public function getInputSpecifications(): SpecificationBag
    {
        if (!$this->inputSpecifications) {
            $this->inputSpecifications = new SpecificationBag($this->expects());
        }

        return $this->inputSpecifications;
    }

    /**
     * @param string $key
     *
     * @return ScalarType|OutputSpecification|mixed|null
     */
    public function getOutput(string $key)
    {
        return $this->getOutputSpecifications()->get($key);
    }

    /**
     * @return SpecificationBag
     */
    public function getOutputSpecifications(): SpecificationBag
    {
        if (!$this->outputSpecifications) {
            $this->outputSpecifications = new SpecificationBag($this->outputs());
        }

        return $this->outputSpecifications;
    }
}