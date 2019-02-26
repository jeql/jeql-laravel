<?php

namespace Jeql\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use Jeql\Bags\SpecificationBag;
use Jeql\Contracts\ReferenceType;
use Jeql\Contracts\Specification;
use Jeql\Contracts\Operation;
use Jeql\InputSpecification;
use Jeql\OperationRegistry;
use Jeql\ScalarTypes\ScalarType;

class IntrospectController
{
    protected $usedInputSpecifications = [];
    protected $usedOutputSpecifications = [];

    /**
     * @param HttpRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(HttpRequest $request, OperationRegistry $operations): \Illuminate\Http\JsonResponse
    {
        $usedOperations = $this->getOperations($operations->all());
        $usedOutputSpecifications = $this->getOutputSpecifications();
        $usedInputSpecifications = $this->getInputSpecifications();

        return response()->json([
            'operations' => $usedOperations,
            'specifications' => [
                'input' => $usedOutputSpecifications,
                'output' => $usedInputSpecifications,
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
            'expects' => $this->getOperationExpectations($reflectionInstance->getInputSpecifications()),
            'outputs' => $this->getOperationOutputs($reflectionInstance->getOutputSpecifications()),
        ];

        return $schema;
    }

    /**
     * @param SpecificationBag $inputDefintions
     *
     * @return array
     * @throws \Exception
     */
    protected function getOperationExpectations(SpecificationBag $inputDefintions): array
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

            if ($expectation instanceof InputSpecification) {
                $expectations[] = [
                    'name' => $fieldName,
                    'type' => get_class($expectation),
                ];

                $this->addInputSpecification($expectation);

                continue;
            }

            throw new \Exception(gettype($expectation) . ' is not a valid InputSpecification');
        }

        return $expectations;
    }

    /**
     * @param SpecificationBag $outputSpecifications
     *
     * @return array
     * @throws \Exception
     */
    protected function getOperationOutputs(SpecificationBag $outputSpecifications): array
    {
        $outputs = [];

        foreach ($outputSpecifications->all() as $fieldName => $output) {
            if ($output instanceof ReferenceType) {
                $subSpecification = $output->getSpecification();

                $outputs[] = [
                    'name' => $fieldName,
                    'type' => get_class($output),
                    'specification' => get_class($subSpecification),
                ];

                $this->addOutputSpecification($subSpecification);

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

            throw new \Exception(gettype($output) . ' is not a valid OutputSpecification');
        }

        return $outputs;
    }

    /**
     * @return array
     */
    protected function getOutputSpecifications(): array
    {
        $outputSpecifications = [];
        $outputSpecification = current($this->usedOutputSpecifications);

        while ($outputSpecification) {
            $outputSpecifications[] = [
                'name' => get_class($outputSpecification),
                'expects' => $this->getOperationExpectations($outputSpecification->getInputSpecifications()),
                'outputs' => $this->getOperationOutputs($outputSpecification->getOutputSpecifications()),
            ];

            $outputSpecification = next($this->usedOutputSpecifications);
        }

        return $outputSpecifications;
    }

    /**
     * @return array
     */
    protected function getInputSpecifications(): array
    {
        $inputSpecifications = [];
        $inputSpecification = current($this->usedInputSpecifications);

        while ($inputSpecification) {
            $inputSpecifications[] = [
                'name' => get_class($inputSpecification),
                'expects' => $this->getOperationExpectations($inputSpecification->getInputSpecifications()),
            ];

            $inputSpecification = next($this->usedInputSpecifications);
        }

        return $inputSpecifications;
    }

    /**
     * @param Specification $inputSpecification
     *
     * @return void
     */
    protected function addInputSpecification(Specification $inputSpecification)
    {
        $className = get_class($inputSpecification);

        if (!in_array($className, $this->usedInputSpecifications)) {
            $this->usedInputSpecifications[$className] = $inputSpecification;
        }
    }

    /**
     * @param Specification $outputSpecification
     *
     * @return void
     */
    protected function addOutputSpecification(Specification $outputSpecification)
    {
        $className = get_class($outputSpecification);

        if (!in_array($className, $this->usedOutputSpecifications)) {
            $this->usedOutputSpecifications[$className] = $outputSpecification;
        }
    }
}