<?php

namespace Jeql\ScalarTypes;

use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\Specification;
use Jeql\OutputSpecification;

class OfType extends ScalarType implements ReferenceType
{
    /** @var string */
    protected $specification;

    /** @var null|Specification */
    protected $specificationInstance;

    /** @var  */
    protected $expecations;

    /**
     * @param string $specification
     */
    public function __construct(string $specification)
    {
        $this->specification = $specification;
    }

    /**
     * Return specification instance, instantiate once per request for Scalar Type
     *
     * @return Specification
     * @throws \Exception
     */
    public function getSpecification(): Specification
    {
        if (!$this->specificationInstance) {
            $this->specificationInstance = OutputSpecification::instantiate($this->specification);
        }

        return $this->specificationInstance;
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

    /**
     * @param array|HasInputSpecifications $argumentsSpecifications
     *
     * @return $this
     */
    public function acceptsArguments($argumentsSpecifications): ReferenceType
    {
        // Set expectations to Output Specification
        $this->getSpecification()->setInputSpecifications($argumentsSpecifications);

        return $this;
    }
}