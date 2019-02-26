<?php

namespace Jeql\Traits;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\ScalarType;
use Jeql\InputSpecification;

trait HandleInputSpecifications
{
    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /**
     * @return array|ScalarType
     */
    abstract protected function expects();

    /**
     * @param string $key
     *
     * @return ScalarType|InputSpecification|null
     */
    public function getInput(string $key)
    {
        return $this->getInputSpecifications()->get($key);
    }

    /**
     * @return SpecificationBag
     * @throws \InvalidArgumentException
     */
    public function getInputSpecifications(): SpecificationBag
    {

        if ($this->inputSpecifications) {
            return $this->inputSpecifications;
        }

        $inputSpecifications = $this->expects();

        if ($inputSpecifications instanceof ReferenceType) {
            $inputSpecification = $inputSpecifications->getSpecification();

            if (!$inputSpecification instanceof HasInputSpecifications) {
                throw new \InvalidArgumentException('Invalid reference given: ' . get_class($inputSpecification) . ' is not an instanceof InputSpecification');
            }

            $this->inputSpecifications = $inputSpecification->getInputSpecifications();

            return $this->inputSpecifications;
        }

        if (is_array($inputSpecifications)) {
            $this->inputSpecifications = new SpecificationBag($inputSpecifications);

            return $this->inputSpecifications;
        }

        throw new \InvalidArgumentException('Defined input in expects() method is invalid, must be an array or instanceof ReferenceType');
    }
}