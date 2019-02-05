<?php

namespace Jeql;

use Jeql\Bags\ArgumentBag;
use Jeql\Contracts\Argument;
use Jeql\Contracts\Definition;
use Jeql\Contracts\HasArguments;

abstract class InputDefinition implements Definition, HasArguments
{
    /** @var null|ArgumentCollection */
    protected $argumentCollection;

    /**
     * @return array
     */
    abstract protected function arguments(): array;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArgument(string $key)
    {
        return $this->getArguments()->get($key);
    }

    /**
     * @return ArgumentBag
     */
    public function getArguments(): ArgumentBag
    {
        if (!$this->argumentCollection) {
            $this->argumentCollection = new ArgumentBag($this->arguments());
        }

        return $this->argumentCollection;
    }
}