<?php

namespace Jeql\Bags;

use Jeql\Request;

class RequestBag extends Bag
{
    /**
     * RequestBag constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $requests = [];

        foreach ($fields as $field) {
            if (!is_array($field)) {
                $requestedFields[$field] = new Request($field);
                continue;
            }

            $fieldRequest = Request::createFromArray($field);

            $requests[$fieldRequest->getName()] = $fieldRequest;
        }

        parent::__construct($requests);
    }
}