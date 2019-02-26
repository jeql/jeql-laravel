<?php

namespace Jeql;

use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\Specification;
use Jeql\Contracts\HasInputSpecifications;
use Jeql\Contracts\HasOutputSpecifications;
use \Jeql\Contracts\Operation as OperationContract;
use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\ListOfType;
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
     * @throws \Exception
     */
    public function respond(Request $request, Specification $specification, $data): array
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

            if ($fieldType instanceof ReferenceType) {
                if (!$this->isFieldDefined($data, $fieldName)) {
                    throw new \Exception("{$fieldName} is missing in response data.");
                }


                $output[$fieldName] = $this->respondToReferenceType($fieldType, $fieldRequest, $fieldValue);

                continue;
            }

            $output[$fieldName] = $fieldValue;
        }

        return $output;
    }

    /**
     * @param ReferenceType $fieldType
     * @param Request $fieldRequest
     * @param mixed $fieldValue
     *
     * @return array
     */
    protected function respondToReferenceType(ReferenceType $fieldType, Request $fieldRequest, $fieldValue): array
    {
        if (!$fieldType instanceof ListOfType) {
            return $this->respond($fieldRequest, $fieldType->getSpecification(), $fieldValue);
        }

        // Handle ListOfType output recursively
        $output = [];

        foreach ($fieldValue ?: [] as $index => $item) {
            $output[$index] = $this->respond($fieldRequest, $fieldType->getSpecification(), $item);
        }

        return $output;
    }

    /**
     * Get the field value from given data by field name
     * 
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
     * Check if field is defined (may be null but not missing)
     *
     * @param mixed $data
     * @param string $fieldName
     *
     * @return bool
     */
    private function isFieldDefined($data, $fieldName): bool
    {
        return is_array($data) ? isset($data[$fieldName]) : isset($data->$fieldName);
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