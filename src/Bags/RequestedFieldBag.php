<?php

namespace Jeql\Bags;

use Jeql\RequestedField;

class RequestedFieldBag extends Bag
{
    /**
     * ArgumentBag constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $requestedFields = [];

        foreach ($fields as $field) {
            if (is_array($field)) {
                $fieldObject = RequestedField::createFromArray($field);

                $requestedFields[$fieldObject->getName()] = $fieldObject;

                continue;
            }

            $requestedFields[$field] = new RequestedField($field);
        }

        parent::__construct($requestedFields);
    }
}