<?php

namespace Jeql\Contracts;

use Jeql\Bags\SpecificationBag;
use Jeql\InputSpecification;

interface HasInputSpecifications
{
    /**
     * @return SpecificationBag
     */
    public function getInputSpecifications(): SpecificationBag;

    /**
     * @param string $key
     *
     * @return ScalarType|InputSpecification|null
     */
    public function getInput(string $key);
}