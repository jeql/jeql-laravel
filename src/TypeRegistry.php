<?php

namespace Jeql;

use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\BooleanType;
use Jeql\ScalarTypes\EnumType;
use Jeql\ScalarTypes\HasManyType;
use Jeql\ScalarTypes\IntegerType;
use Jeql\ScalarTypes\StringType;
use Jeql\ScalarTypes\TimestampType;

class TypeRegistry
{
    /**
     * Alias of the `integer()` method.
     *
     * @return ScalarType
     */
    public function int(): ScalarType
    {
        return $this->integer();
    }

    /**
     * @return ScalarType
     */
    public function integer(): ScalarType
    {
        return new IntegerType;
    }

    /**
     * @return ScalarType
     */
    public function string(): ScalarType
    {
        return new StringType;
    }

    /**
     * Alias of the `boolean()` method.
     *
     * @return ScalarType
     */
    public function bool(): ScalarType
    {
        return $this->boolean();
    }

    /**
     * @return ScalarType
     */
    public function boolean(): ScalarType
    {
        return new BooleanType;
    }

    /**
     * @param array $options
     *
     * @return ScalarType
     */
    public function enum(array $options): ScalarType
    {
        return new EnumType($options);
    }

    /**
     * @param string|null $format
     *
     * @return ScalarType
     */
    public function timestamp(string $format = null): ScalarType
    {
        return new TimestampType($format);
    }

    /**
     * @param string $specification
     *
     * @return ScalarType
     */
    public function hasMany(string $specification): ScalarType
    {
        return new HasManyType($specification);
    }
}