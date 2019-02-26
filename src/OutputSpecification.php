<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ScalarType;

abstract class OutputSpecification implements Specification, HasOutputSpecifications, HasInputSpecifications
{
    /** @var null|SpecificationBag */
    protected $outputSpecifications;

    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /** @var array */
    protected static $__specifications = [];

    /**
     * @return array
     */
    abstract protected function outputs(): array;

    /**
     * Instatiate output specification
     *
     * @param string $classname
     *
     * @return Specification
     * @throws \UnexpectedValueException
     */
    public static function instantiate(string $classname): Specification
    {
        $instance = new $classname;

        // Make sure instance is instance of Specification
        if (!$instance instanceof Specification) {
            throw new \UnexpectedValueException(
                sprintf('%s must be an instance of %s', $classname, Specification::class)
            );
        }

        return $instance;
    }

    /**
     * Instatiate output specification once
     *
     * @param string $classname
     *
     * @return Specification
     */
    public static function instantiateOnce(string $classname): Specification
    {
        // See if already instantiate once
        if ($instance = static::$__specifications[$classname] ?? '') {
            return $instance;
        }

        $instance = static::instantiate($classname);

        // Store instance for reusability
        static::$__specifications[$classname] = $instance;

        return $instance;
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

    /**
     * @param array $expectations
     *
     * @return void
     */
    public function setInputSpecifications(array $expectations)
    {
        $this->inputSpecifications = new SpecificationBag($expectations);
    }

    /**
     * @return SpecificationBag
     */
    public function getInputSpecifications(): SpecificationBag
    {
        // Make sure input specification is always an instance of Specification Bag
        if (!$this->inputSpecifications) {
            $this->inputSpecifications = new SpecificationBag([]);
        }

        return $this->inputSpecifications;
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
}