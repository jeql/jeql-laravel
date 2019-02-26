<?php

namespace Jeql;

use Illuminate\Support\Facades\Validator;
use Jeql\Bags\ArgumentBag;
use Jeql\Bags\SpecificationBag;
use Jeql\Bags\RequestBag;
use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\HasOutputSpecifications;
use Jeql\Contracts\ScalarType;
use Jeql\Exceptions\ValidationException;

class JeqlValidator
{
    /**
     * @param Specification $specification
     * @param Request $request
     */
    public function validate(Specification $specification, Request $request)
    {
        if ($specification instanceof HasInputSpecifications) {
            $this->validateArguments($specification->getInputSpecifications(), $request->getArguments());
        }

        if ($specification instanceof HasOutputSpecifications) {
            $this->validateFields($specification->getOutputSpecifications(), $request->getFields());
        }
    }

    /**
     * @param SpecificationBag $specifiedInput
     * @param ArgumentBag $givenArguments
     *
     * @throws \Exception
     */
    protected function validateArguments(SpecificationBag $specifiedInput, ArgumentBag $givenArguments)
    {
        $rules = [];
        $validatedArguments = [];

        // Validate argument syntax
        foreach ($specifiedInput->all() as $key => $input) {
            // Store are validated arguments
            $validatedArguments[] = $key;

            $value = $givenArguments->get($key);

            if ($input instanceof InputSpecification) {
                if (!$value instanceof ArgumentBag) {
                    throw new \Exception("Invalid argument for {$key}, expecting array");
                }

                $this->validateArguments($input->getInputSpecifications(), $value);

                continue;
            }

            if ($input instanceof ScalarType) {
                $input->validate($key, $value);

                // Store rules in variable when present
                if (!$rules = $input->getRules()) {
                    $rules[$key] = $rules;
                }

                continue;
            }

            throw new \Exception("Invalid input specification for {$key}");
        }

        // See if undefined arguments are given
        foreach ($givenArguments->all() as $name => $value) {
            if (!in_array($name, $validatedArguments)) {
                throw new \Exception("{$name} is not defined as an argument");
            }
        }

        // Validate input rules
        $validator = Validator::make($givenArguments->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param SpecificationBag $specifiedOuput
     * @param RequestBag $requestFields
     *
     * @throws \Exception
     */
    protected function validateFields(SpecificationBag $specifiedOuput, RequestBag $requestFields)
    {
        /** @var Request $requestedField */
        foreach ($requestFields->all() as $requestedField) {
            $name = $requestedField->getName();
            $subFields = $requestedField->getFields();

            if (!$specifiedOuput->has($name)) {
                throw new \Exception("Syntax error: requested field {$name} is not defined");
            }

            if ($subFields->isNotEmpty()) {
                $subFieldSpecification = $specifiedOuput->get($name);

                if ($subFieldSpecification instanceof ReferenceType) {
                    $this->validate($subFieldSpecification->getSpecification(), $requestedField);

                    continue;
                }

                throw new \Exception("Invalid output specification for {$name}, expecting array");
            }
        }
    }
}