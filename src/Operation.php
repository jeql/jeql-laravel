<?php

namespace Jeql;

use Jeql\Bags\DefinitionBag;
use Jeql\Bags\OutputBag;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasInputDefinitions;
use Jeql\Contracts\HasOutputDefinitions;
use \Jeql\Contracts\Operation as OperationContract;
use Jeql\Contracts\ScalarType;
use Jeql\ScalarTypes\HasManyType;

abstract class Operation implements Definition, OperationContract, HasInputDefinitions, HasOutputDefinitions
{
    /** @var null|DefinitionBag */
    protected $inputDefinitions;

    /** @var null|DefinitionBag */
    protected $outputDefinitions;

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
     * @param $definition
     * @param mixed $data
     *
     * @return array
     */
    public function respond(Request $request, Definition $definition, $data)
    {
        $output = [];
        $requestedFields = $request->getFields();

        /** @var Request $fieldRequest */
        foreach ($requestedFields->all() as $fieldRequest) {
            $fieldName = $fieldRequest->getName();
            $fieldValue = $this->getFieldValue($data, $fieldName);
            $fieldType = $definition->getOutput($fieldName);

            // Validate field value when field type is instanceof ScalarType
            if ($fieldType instanceof ScalarType) {
                $fieldType->validate($fieldName, $fieldValue);
            }

            // Handle hasMany relation output recursively
            if ($fieldType instanceof HasManyType) {
                foreach ($fieldValue as $index => $item) {
                    $output[$fieldName][$index] = $this->respond($fieldRequest, $fieldType->getDefinition(), $item);
                }

                continue;
            }

            // Handle definition output recursively
            if ($fieldType instanceof Definition) {
                $output[$fieldName] = $this->respond($fieldRequest, $fieldType, $fieldValue);

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
     * @return ScalarType|InputDefinition|null
     */
    public function getInput(string $key)
    {
        $expectedValues = $this->expects();

        return $expectedValues[$key] ?? null;
    }

    /**
     * @return DefinitionBag
     */
    public function getInputDefinitions(): DefinitionBag
    {
        if (!$this->inputDefinitions) {
            $this->inputDefinitions = new DefinitionBag($this->expects());
        }

        return $this->inputDefinitions;
    }

    /**
     * @param string $key
     *
     * @return ScalarType|OutputDefinition|mixed|null
     */
    public function getOutput(string $key)
    {
        return $this->getOutputDefinitions()->get($key);
    }

    /**
     * @return DefinitionBag
     */
    public function getOutputDefinitions(): DefinitionBag
    {
        if (!$this->outputDefinitions) {
            $this->outputDefinitions = new DefinitionBag($this->outputs());
        }

        return $this->outputDefinitions;
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