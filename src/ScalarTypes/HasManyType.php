<?php

namespace Jeql\ScalarTypes;

use Jeql\OutputDefinition;

class HasManyType extends ScalarType
{
    /** @var OutputDefinition */
    protected $definition;

    /**
     * @param OutputDefinition $definition
     */
    public function __construct(OutputDefinition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return OutputDefinition
     */
    public function getDefinition(): OutputDefinition
    {
        return $this->definition;
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
