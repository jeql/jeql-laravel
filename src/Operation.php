<?php

namespace Jeql;

use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\HasOutputSpecifications;
use \Jeql\Contracts\Operation as OperationContract;
use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\ListOfType;
use Jeql\ScalarTypes\OfType;

abstract class Operation implements Specification, OperationContract, HasInputSpecifications, HasOutputSpecifications
{
    /** @var null|SpecificationBag */
    protected $inputSpecifications;

    /** @var null|SpecificationBag */
    protected $outputSpecifications;

    /**
     * @param Request $request
     *
     * @return mixed
     */
    abstract protected function resolve(Request $request);

    /**
     * Handle operation request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->resolve($request);

        $response = $this->respond($request, $this, $data);

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @param Specification $specification
     * @param mixed $data
     *
     * @return array
     */
    public function respond(Request $request, Specification $specification, $data)
    {
        $output = [];
        $requestedFields = $request->getFields();

        /** @var Request $fieldRequest */
        foreach ($requestedFields->all() as $fieldRequest) {
            $fieldName = $fieldRequest->getName();
            $fieldValue = $this->getFieldValue($data, $fieldName);
            $fieldType = $specification->getOutput($fieldName);

            // Validate field value when field type is instanceof ScalarType
            if ($fieldType instanceof ScalarType) {
                $fieldType->validate($fieldName, $fieldValue);
                $fieldValue = $fieldType->format($fieldValue, $fieldRequest->getArguments());
            }

            // Handle ListOfType output recursively
            if ($fieldType instanceof ListOfType) {
                foreach ($fieldValue as $index => $item) {
                    $output[$fieldName][$index] = $this->respond($fieldRequest, $fieldType->getSpecification(), $item);
                }

                continue;
            }

            // Handle specification output recursively
            if ($fieldType instanceof OfType) {
                $output[$fieldName] = $this->respond($fieldRequest, $fieldType->getSpecification(), $fieldValue);

                continue;
            }

            $output[$fieldName] = $fieldValue;
        }

        return $output;
    }

    /**
     * @param mixed $data
     * @param string $fieldName
     *
     * @return mixed
     */
    private function getFieldValue($data, $fieldName)
    {
        if ($fieldValue = data_get($data, $fieldName)) {
            return $fieldValue;
        }

        return data_get($data, camel_case($fieldName));
    }

    /**
     * @param string $key
     *
     * @return ScalarType|InputSpecification|null
     */
    public function getInput(string $key)
    {
        $expectedValues = $this->expects();

        return $expectedValues[$key] ?? null;
    }

    /**
     * @return SpecificationBag
     */
    public function getInputSpecifications(): SpecificationBag
    {

        if ($this->inputSpecifications) {
            return $this->inputSpecifications;
        }

        $inputSpecifications = $this->expects();

        // Return specification bag from given output specification classname
        if (is_string($inputSpecifications)) {
            $inputSpecification = InputSpecification::instantiateOnce($inputSpecifications);

            $this->inputSpecifications = $inputSpecification->getInputSpecifications();

            return $this->inputSpecifications;
        }

        // Return specification bag from given output specification
        if ($inputSpecifications instanceof HasInputSpecifications) {
            $this->inputSpecifications = $inputSpecifications->getInputSpecifications();

            return $this->inputSpecifications;
        }

        // Return specification bag from given array
        $this->inputSpecifications = new SpecificationBag((array)$inputSpecifications);

        return $this->inputSpecifications;
    }

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
     */
    public function getOutputSpecifications(): SpecificationBag
    {
        if ($this->outputSpecifications) {
            return $this->outputSpecifications;
        }

        $outputSpecifications = $this->outputs();

        // Return specification bag from given output specification classname
        if (is_string($outputSpecifications)) {
            $outputSpecification = OutputSpecification::instantiateOnce($outputSpecifications);

            $this->outputSpecifications = $outputSpecification->getOutputSpecifications();

            return $this->outputSpecifications;
        }

        // Return specification bag from given output specification
        if ($outputSpecifications instanceof HasOutputSpecifications) {
            $this->outputSpecifications = $outputSpecifications->getOutputSpecifications();

            return $this->outputSpecifications;
        }

        // Return specification bag from given array
        $this->outputSpecifications = new SpecificationBag((array)$outputSpecifications);

        return $this->outputSpecifications;
    }

    /**
     * Overwrite to define the operation's expected arguments
     *
     * @return array
     */
    public function expects(): array
    {
        return [];
    }

    /**
     * Overwrite to define the operation' output
     *
     * @return array
     */
    public function outputs(): array
    {
        return [];
    }
}