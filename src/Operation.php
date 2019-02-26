<?php

namespace Jeql;

use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\HasOutputSpecifications;
use \Jeql\Contracts\Operation as OperationContract;
use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\ListOfType;
use Jeql\ScalarTypes\OfType;
use Jeql\Traits\HandleInputSpecifications;
use Jeql\Traits\HandleOutputSpecifications;

abstract class Operation implements Specification, OperationContract, HasInputSpecifications, HasOutputSpecifications
{
    use HandleOutputSpecifications, HandleInputSpecifications;

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
     * Overwrite to define the operation's expected arguments
     *
     * @return array|ScalarType
     */
    public function expects()
    {
        return [];
    }

    /**
     * Overwrite to define the operation' output
     *
     * @return array|ScalarType
     */
    public function outputs()
    {
        return [];
    }
}