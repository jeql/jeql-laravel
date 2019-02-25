<?php

namespace Jeql\ScalarTypes;

use Carbon\Carbon;
use Jeql\Bags\ArgumentBag;

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
        return strtotime($value) || $value instanceof Carbon;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message(): string
    {
        return ":attribute must be a valid timestamp or Carbon object";
    }

    /**
     * Format the given value for specified format
     *
     * @param mixed $value
     * @param ArgumentBag $arguments
     *
     * @return mixed
     */
    public function format($value, ArgumentBag $arguments)
    {
        $format = $arguments->get('format') ?: $this->format;

        if ($value instanceof Carbon) {
            return $value->format($format);    
        }

        return date($format, strtotime($value));
    }
}
