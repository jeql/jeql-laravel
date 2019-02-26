<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ScalarType;
use Jeql\Traits\HandleOutputSpecifications;

abstract class OutputSpecification implements Specification, HasOutputSpecifications, HasInputSpecifications
{
    use HandleOutputSpecifications;

    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /** @var array */
    protected static $__specifications = [];

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
     * @param array|HasInputSpecifications $expectations
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setInputSpecifications($expectations)
    {
        if ($expectations instanceof HasInputSpecifications) {
            $this->inputSpecifications = $expectations->getInputSpecifications();

            return;
        }

        if (is_array($expectations)) {
            $this->inputSpecifications = new SpecificationBag($expectations);

            return;
        }

        throw new \InvalidArgumentException('Argument must be an array or instanceof InputSpecification');
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