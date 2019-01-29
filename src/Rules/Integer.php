<?php

namespace Jeql\Rules;

use Illuminate\Contracts\Validation\Rule;

class Integer implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return is_int($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return ':attribute must be an integer scalar type.';
    }
}
