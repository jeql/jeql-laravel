<?php

namespace Jeql\ScalarTypes;

class EnumType extends ScalarType
{
    /** @var array */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return in_array($value, $this->options);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        $options = implode(', ', $this->options);

        return ":attribute must be equal to: {$options}";
    }
}
