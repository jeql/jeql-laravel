<?php

namespace Jeql;

use Jeql\Bags\OutputBag;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasOutput;
use \Jeql\Contracts\Operation as OperationContract;

abstract class Operation extends InputDefinition implements Definition, OperationContract, HasOutput
{
    /** @var null|OutputBag */
    protected $outputCollection;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    abstract protected function resolve(): \Illuminate\Http\JsonResponse;

    /**
     * Handle operation request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): \Illuminate\Http\JsonResponse
    {
        $this->validateRequest();

        return $this->resolve();
    }

    /**
     * Validate the request based on operation definitions
     *
     * @return void
     * @throws \Exception
     */
    protected function validateRequest()
    {
        return;
    }

    /**
     * @param string $key
     *
     * @return ScalarType|OutputDefinition|null
     */
    public function getField(string $key)
    {
        return $this->getFields()->get($key);
    }

    /**
     * @return OutputBag
     */
    public function getFields(): OutputBag
    {
        if (!$this->outputCollection) {
            $this->outputCollection = new OutputBag($this->fields());
        }

        return $this->outputCollection;
    }

    /**
     * Overwrite to define the operation's arguments
     *
     * @return array
     */
    public function arguments(): array
    {
        return [];
    }

    /**
     * Overwrite to define the operation's output
     *
     * @return array
     */
    public function outputs(): array
    {
        return [];
    }
}