<?php

namespace Jeql;

use Jeql\Bags\ArgumentBag;
use Jeql\Bags\OutputBag;
use Jeql\Bags\RequestedFieldBag;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasArguments;
use Jeql\Contracts\HasOutput;
use Jeql\Contracts\ScalarType;
use Illuminate\Validation\Validator;

class JeqlValidator
{
    /**
     * @param Definition $definition
     * @param Context $context
     */
    public function validate(Definition $definition, Context $context) // def = Operation, con = Request
    {
        if ($definition instanceof HasArguments) {
            $this->validateArguments($definition->getArguments(), $context->getArguments());
        }

        if ($definition instanceof HasOutput) {
            $this->validateFields($definition->getFields(), $context->getFields());
        }
    }

    /**
     * @param ArgumentBag $definedArguments
     * @param ArgumentBag $givenArguments
     *
     * @throws \Exception
     */
    protected function validateArguments(ArgumentBag $definedArguments, ArgumentBag $givenArguments)
    {
        $rules = [];

        // Validate argument syntax
        foreach ($definedArguments as $key => $argument) {
            $value = $givenArguments->get($key);

            if ($argument instanceof InputDefinition) {
                if (!$value instanceof ArgumentBag) {
                    throw new \Exception("Invalid argument for {$key}, expecting array");
                }

                $this->validateArguments($argument->getFields(), $value);

                continue;
            }

            if ($argument instanceof ScalarType) {
                $argument->validate($value);

                // Store rules in variable when present
                if (!$rules = $argument->getRules()) {
                    $rules[$key] = $rules;
                }

                continue;
            }

            throw new \Exception("Invalid input definition for {$key}");
        }

        // Validate input rules
        $validator = Validator::make($givenArguments->all(), $rules);

        if ($validator->fails()) {
            //throw new ValidationException($validator->getFields());
            throw new \Exception('A validation exception occured');
        }
    }

    /**
     * @param OutputBag $definedFields
     * @param RequestedFieldBag $requestFields
     *
     * @throws \Exception
     */
    protected function validateFields(OutputBag $definedFields, RequestedFieldBag $requestFields)
    {
        foreach ($requestFields as $requestedField) {
            $name = $requestedField->getName();

            /** @var RequestedFieldBag $fields */
            if ($fields = $requestedField->getFields()) {
                $subFieldDefinition = $definedFields->getField($name);

                if (!$subFieldDefinition instanceof InputDefinition) {
                    throw new \Exception("Invalid output definition for {$name}, expecting array");
                }

                $this->validate($subFieldDefinition, $requestedField);
            }

            if (!$definedFields->has($name)) {
                throw new \Exception("Syntax error: requested field {$name} does not exists");
            }
        }
    }
}