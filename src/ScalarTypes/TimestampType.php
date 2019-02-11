<?php

namespace Jeql\ScalarTypes;

class TimestampType extends ScalarType
{
    /**
     * Default format: Unix timestamp
     *
     * @var string
     */
    protected $format = 'U';

    /**
     * @param string|null $format
     */
    public function __construct(string $format = null)
    {
        if ($format) {
            $this->format = $format;
        }
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
        return strtotime($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return ":attribute must be a valid timestamp";
    }

    /**
     * Format the given value for specified format
     *
     * @param mixed $value
     * @param string|null $format
     *
     * @return mixed
     */
    public function format($value, $format = null)
    {
        $format = $format ?: $this->format;

        return date($format, strtotime($value));
    }
}
