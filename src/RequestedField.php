<?php

namespace Jeql;

use Jeql\Bags\RequestedFieldBag;
use Jeql\Bags\ArgumentBag;

class RequestedField extends Context
{
    /** @var string */
    protected $name;

    /** @var RequestedFieldBag */
    protected $fields;

    /** @var ArgumentBag */
    protected $arguments;

    public function __construct(string $name, array $fields = [], array $arguments = [])
    {
        $this->name = $name;
        $this->fields = $this->setFields($fields);
        $this->arguments = $this->setArguments($arguments);
    }

    /**
     * @param array $field
     *
     * @return static
     * @throws \Exception
     */
    public static function createFromArray(array $field)
    {
        $name = $field['name'] ?? null;

        if (!$name) {
            throw new \Exception("Syntax error: Requested fields always need a name ('fieldname' or ['name' => 'fieldname']");
        }

        $fields = $field['fields'] ?? [];
        $arguments = $field['arguments'] ?? [];

        return new static($name, $fields, $arguments);
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    protected function setFields(array $fields): self
    {
        $this->fields = new RequestedFieldBag($fields);

        return $this;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    protected function setArguments(array $arguments): self
    {
        $this->arguments = new ArgumentBag($arguments);

        return $this;
    }

    /**
     * @return RequestedFieldBag
     */
    public function getFields(): RequestedFieldBag
    {
        return $this->fields;
    }

    /**
     * @param string $key
     *
     * @return RequestedField|null
     */
    public function getField(string $key): ?self
    {
        $this->getFields()->get($key);
    }

    /**
     * @return ArgumentBag
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArgument(string $key)
    {
        return $this->getArguments()->get($key);
    }
}