<?php

namespace Jeql;

use Jeql\Bags\ArgumentBag;
use Jeql\Bags\RequestBag;
use Jeql\Contracts\HasArguments;
use Jeql\Contracts\HasFieldRequests;

class Request implements HasArguments, HasFieldRequests
{
    /** @var string */
    protected $name;

    /** @var ArgumentBag */
    protected $argumentBag;

    /** @var RequestBag */
    protected $requestedFieldBag;

    /**
     * Request constructor.
     *
     * @param string $name
     * @param array $arguments
     * @param array $fields
     */
    public function __construct(string $name, array $arguments = [], array $fields = [])
    {
        $this->name = $name;
        $this->argumentBag = new ArgumentBag($arguments);
        $this->requestedFieldBag = new RequestBag($fields);
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
            throw new \Exception("Syntax error: Request Fields always need a name ('fieldname' or ['name' => 'fieldname']");
        }

        $arguments = $field['arguments'] ?? [];
        $fields = $field['fields'] ?? [];

        return new static($name, $arguments, $fields);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ArgumentBag
     */
    public function getArguments(): ArgumentBag
    {
        return $this->argumentBag;
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

    /**
     * @return RequestBag
     */
    public function getFields(): RequestBag
    {
        return $this->requestedFieldBag;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getField(string $key)
    {
        return $this->getFields()->get($key);
    }
}