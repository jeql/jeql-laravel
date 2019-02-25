<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\Argument;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\ScalarType;

abstract class InputSpecification implements Specification, HasInputSpecifications
{
    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /** @var array */
    protected static $__specifications = [];

    /**
     * @return array
     */
    abstract protected function expects(): array;

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
            throw new \Exception("{$classname} is not an instance of InputSpecification");
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
}