<?php

namespace Jeql;

use Jeql\Contracts\Argument;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Traits\HandleInputSpecifications;

abstract class InputSpecification implements Specification, HasInputSpecifications
{
    use HandleInputSpecifications;

    /** @var array */
    protected static $__specifications = [];

    /**
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
}