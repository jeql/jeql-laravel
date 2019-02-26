<?php

namespace Jeql\ScalarTypes;

use Jeql\Contracts\Specification;
use Jeql\OutputSpecification;

class OfType extends ScalarType
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
     * @param $expectations
     *
     * @return $this
     */
    public function withExpectations($expectations): self
    {
        // Set expectations to Output Specification
        $this->getSpecification()->setInputSpecifications($expectations);

        return $this;
    }
}