<?php

namespace Jeql;

use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\IntegerType;
use Jeql\ScalarTypes\StringType;

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
}