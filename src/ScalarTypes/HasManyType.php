<?php

namespace Jeql\ScalarTypes;

use Jeql\Contracts\Definition;
use Jeql\OutputDefinition;

class HasManyType extends ScalarType
{
    /** @var OutputDefinition */
    protected $definition;

    /**
     * @param string $definition
     */
    public function __construct(string $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return Definition
     */
    public function getDefinition(): Definition
    {
        return OutputDefinition::instantiate($this->definition);
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
