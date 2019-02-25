<?php

namespace Jeql\ScalarTypes;

use Jeql\Contracts\Specification;
use Jeql\OutputSpecification;

class OfType extends ScalarType
{
    /** @var OutputSpecification */
    protected $specification;

    /**
     * @param string $specification
     */
    public function __construct(string $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @return Specification
     */
    public function getSpecification(): Specification
    {
        return OutputSpecification::instantiate($this->specification);
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
        return true;
    }
}