<?php

namespace Jeql\Contracts;

use Jeql\Bags\RequestedFieldBag;
use Jeql\RequestedField;

interface HasRequestedFields
{
    /**
     * @return RequestedFieldBag
     */
    public function getFields();

    /**
     * @param string $key
     *
     * @return RequestedField|null
     */
    public function getField(string $key);
}