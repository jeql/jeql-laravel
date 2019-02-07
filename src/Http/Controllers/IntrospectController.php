<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Jeql\Bags\DefinitionBag;
use Jeql\Contracts\Definition;
use Jeql\Contracts\Operation;
use Jeql\InputDefinition;
use Jeql\OperationRegistry;
use Jeql\OutputDefinition;
use Jeql\ScalarTypes\HasManyType;
use Jeql\ScalarTypes\ScalarType;

class IntrospectController
{
    protected $usedInputDefinitions = [];
    protected $usedOutputDefinitions = [];

    /**
     * @param HttpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(HttpRequest $request, OperationRegistry $operations): \Illuminate\Http\JsonResponse
    {
        $usedOperations = $this->getOperations($operations->all());
        $usedOutputDefinitions = $this->getOutputDefinitions();
        $usedInputDefinitions = $this->getInputDefinitions();

        return response()->json([
            'operations' => $usedOperations,
            'definitions' => [
                'input' => $usedInputDefinitions,
                'output' => $usedOutputDefinitions,
            ],
        ]);
    }

    /**
     * @param $definedOperations
     *
     * @return array
     */
    protected function getOperations($definedOperations): array
    {
        $operations = [];

        // @todo create introspect handling
        foreach ($definedOperations as $endpoint => $operationClass) {
            $operations[] = $this->buildOperationSchema($endpoint, $operationClass);
        }

        return $operations;
    }

    /**
     * @param string $endpoint
     * @param string $operationClass
     *
     * @return array
     * @throws \Exception
     */
    protected function buildOperationSchema(string $endpoint, string $operationClass): array
    {
        $reflectionClass = new \ReflectionClass($operationClass);
        $reflectionProperties = $reflectionClass->getProperties();
        $reflectionInstance = $reflectionClass->newInstance();

        if (!$reflectionInstance instanceof Operation) {
            throw new \Exception("{$operationClass} must be instanceof Contracts\\Operation");
        }

        $schema = [
            'name' => $reflectionClass->getShortName(),
            'endpoint' => $endpoint,
            'description' => $reflectionProperties['description'] ?? 'No description defined',
            'requires' => [
                'authentication' => false,
            ],
            'expects' => $this->getOperationExpectations($reflectionInstance->getInputDefinitions()),
            'outputs' => $this->getOperationOutputs($reflectionInstance->getOutputDefinitions()),
        ];

        return $schema;
    }

    /**
     * @param DefinitionBag $inputDefintions
     *
     * @return array
     * @throws \Exception
     */
    protected function getOperationExpectations(DefinitionBag $inputDefintions): array
    {
        $expectations = [];

        foreach ($inputDefintions->all() as $fieldName => $expectation) {
            if ($expectation instanceof ScalarType) {
                $expectations[] = [
                    'name' => $fieldName,
                    'type' => get_class($expectation),
                    'example' => 'an example',
                ];

                continue;
            }

            if ($expectation instanceof InputDefinition) {
                $expectations[] = [
                    'name' => $fieldName,
                    'type' => get_class($expectation),
                ];

                $this->addInputDefinition($expectation);

                continue;
            }

            throw new \Exception(gettype($expectation) . ' is not a valid InputDefinition');
        }

        return $expectations;
    }

    /**
     * @param DefinitionBag $outputDefinitions
     *
     * @return array
     * @throws \Exception
     */
    protected function getOperationOutputs(DefinitionBag $outputDefinitions): array
    {
        $outputs = [];

        foreach ($outputDefinitions->all() as $fieldName => $output) {
            if ($output instanceof HasManyType) {
                $subDefinition = $output->getDefinition();

                $outputs[] = [
                    'name' => $fieldName,
                    'type' => get_class($output),
                    'definition' => get_class($subDefinition),
                ];

                $this->addOutputDefinition($subDefinition);

                continue;
            }

            if ($output instanceof ScalarType) {
                $outputs[] = [
                    'name' => $fieldName,
                    'type' => get_class($output),
                    'example' => 'an example',
                ];

                continue;
            }

            if ($output instanceof OutputDefinition) {
                $outputs[] = [
                    'name' => $fieldName,
                    'type' => get_class($output),
                ];

                $this->addOutputDefinition($output);

                continue;
            }

            throw new \Exception(gettype($output) . ' is not a valid OutputDefinition');
        }

        return $outputs;
    }

    /**
     * @return array
     */
    protected function getOutputDefinitions(): array
    {
        $outputDefinitions = [];
        $outputDefinition = current($this->usedOutputDefinitions);

        while ($outputDefinition) {
            $outputDefinitions[] = [
                'name' => get_class($outputDefinition),
                'expects' => $this->getOperationExpectations($outputDefinition->getInputDefinitions()),
                'outputs' => $this->getOperationOutputs($outputDefinition->getOutputDefinitions()),
            ];

            $outputDefinition = next($this->usedOutputDefinitions);
        }

        return $outputDefinitions;
    }

    /**
     * @return array
     */
    protected function getInputDefinitions(): array
    {
        $inputDefinitions = [];
        $inputDefinition = current($this->usedInputDefinitions);

        while ($inputDefinition) {
            $inputDefinitions[] = [
                'name' => get_class($inputDefinition),
                'expects' => $this->getOperationExpectations($inputDefinition->getInputDefinitions()),
            ];

            $inputDefinition = next($this->usedInputDefinitions);
        }

        return $inputDefinitions;
    }

    /**
     * @param Definition $inputDefinition
     *
     * @return void
     */
    protected function addInputDefinition(Definition $inputDefinition)
    {
        $className = get_class($inputDefinition);

        if (!in_array($className, $this->usedInputDefinitions)) {
            $this->usedInputDefinitions[$className] = $inputDefinition;
        }
    }

    /**
     * @param Definition $outputDefintiion
     *
     * @return void
     */
    protected function addOutputDefinition(Definition $outputDefintiion)
    {
        $className = get_class($outputDefintiion);

        if (!in_array($className, $this->usedOutputDefinitions)) {
            $this->usedOutputDefinitions[$className] = $outputDefintiion;
        }
    }
}