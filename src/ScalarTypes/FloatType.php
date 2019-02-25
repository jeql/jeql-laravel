<?php

namespace Jeql\ScalarTypes;

class FloatType extends ScalarType
{
    /**
     * Determine if the validation rule passes.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return is_float($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return ':attribute must be an float.';
    }
}
