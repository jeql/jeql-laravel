<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ScalarType;

abstract class OutputSpecification implements Specification, HasOutputSpecifications
{
    /** @var null|SpecificationBag */
    protected $outputSpecifications;

    /** @var array */
    protected static $__specifications = [];

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