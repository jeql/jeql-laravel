<?php

namespace Jeql\ScalarTypes;

class ListOfType extends OfType
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
        return is_array($value) || method_exists($value, 'toArray');
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return ':attribute must be a array or collection.';
    }
}
