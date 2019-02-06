<?php

namespace Jeql;

use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\Integer;

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
        return new Integer;
    }

    /**
     * @return ScalarType
     */
    public function string(): ScalarType
    {
        return new String;
    }
}