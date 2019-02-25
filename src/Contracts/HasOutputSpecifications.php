<?php

namespace Jeql\Contracts;

use Jeql\Bags\SpecificationBag;
use Jeql\OutputSpecification;

interface HasOutputSpecifications
{
    /**
     * @return SpecificationBag
     */
    public function getOutputSpecifications();

    /**
     * @param string $key
     *
     * @return ScalarType|OutputSpecification|null
     */
    public function getOutput(string $key);
}