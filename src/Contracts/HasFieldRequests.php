<?php

namespace Jeql\Contracts;

use Jeql\Bags\RequestBag;
use Jeql\Request;

interface HasFieldRequests
{
    /**
     * @return RequestBag
     */
    public function getFields();

    /**
     * @param string $key
     *
     * @return Request|null
     */
    public function getField(string $key);
}