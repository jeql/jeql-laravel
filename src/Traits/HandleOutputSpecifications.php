<?php

namespace Jeql\Traits;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\ScalarType;
use Jeql\OutputSpecification;

trait HandleOutputSpecifications
{
    /** @var null|SpecificationBag */
    protected $outputSpecifications;

    /**
     * @return array|ScalarType
     */
    abstract protected function outputs();

    /**
     * @param string $key
     *
     * @return ScalarType|OutputSpecification|mixed|null
     */
    public function getOutput(string $key)
    {
        return $this->getOutputSpecifications()->get($key);
    }

    /**
     * @return SpecificationBag
     * @throws \InvalidArgumentException
     */
    public function getOutputSpecifications(): SpecificationBag
    {
        if ($this->outputSpecifications) {
            return $this->outputSpecifications;
        }

        $outputSpecifications = $this->outputs();

        if ($outputSpecifications instanceof ReferenceType) {
            $outputSpecification = $outputSpecifications->getSpecification();

            if (!$outputSpecification instanceof HasOutputSpecifications) {
                throw new \InvalidArgumentException('Invalid reference given: ' . get_class($outputSpecification) . ' is not an instanceof OutputSpecification');
            }
            $this->outputSpecifications = $outputSpecification->getOutputSpecifications();

            return $this->outputSpecifications;
        }

        if (is_array($outputSpecifications)) {
            $this->outputSpecifications = new SpecificationBag($outputSpecifications);

            return $this->outputSpecifications;
        }

        throw new \InvalidArgumentException('Defined output in outputs() method is invalid, must be an array or instanceof ReferenceType');
    }
}