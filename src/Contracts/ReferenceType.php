<?php

namespace Jeql\Contracts;

interface ReferenceType
{
    /**
     * @return Specification
     */
    public function getSpecification(): Specification;

    /**
     * @param array|HasInputSpecifications $expectations
     *
     * @return ReferenceType
     */
    public function acceptsArguments($expectations): ReferenceType;
}