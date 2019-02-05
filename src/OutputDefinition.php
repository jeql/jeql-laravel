<?php

namespace Jeql;

use Jeql\Contracts\Argument;
use Jeql\Contracts\Definition;
use Jeql\Contracts\Field;
use Jeql\Contracts\HasArguments;
use Jeql\Contracts\HasOutput;

abstract class OutputDefinition implements Definition, HasArguments, HasOutput
{
    /** @var null|ArgumentCollection */
    protected $argumentCollection;

    /** @var null|FieldCollection */
    protected $fieldCollection;

    /**
     * @return array
     */
    abstract protected function arguments(): array;

    /**
     * @return array
     */
    abstract protected function fields(): array;

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

    /**
     * @param string $key
     *
     * @return Field|null
     */
    public function getField(string $key): ?Field
    {
        return $this->getFields()->get($key);
    }

    /**
     * @return FieldCollection
     */
    public function getFields(): FieldCollection
    {
        if (!$this->fieldCollection) {
            $this->fieldCollection = new FieldCollection($this->fields());
        }

        return $this->fieldCollection;
    }
}