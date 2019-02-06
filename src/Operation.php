<?php

namespace Jeql;

use Jeql\Bags\DefinitionBag;
use Jeql\Bags\OutputBag;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasInputDefinitions;
use Jeql\Contracts\HasOutputDefinitions;
use \Jeql\Contracts\Operation as OperationContract;
use Jeql\Contracts\ScalarType;

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

        return response()->json($data);
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