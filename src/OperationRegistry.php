<?php

namespace Jeql;

class OperationRegistry
{
    /** @var array */
    protected $registered = [];

    /**
     * @param string $route
     * @param string $operationClassName
     *
     * @return void
     */
    public function add(string $route, string $operationClassName)
    {
        $this->registered[$route] = $operationClassName;
    }

    /**
     * @param $route
     *
     * @return \Jeql\Contracts\Operation
     * @throws \Exception
     */
    public function match($route): \Jeql\Contracts\Operation
    {
        if (!isset($this->registered[$route])) {
            throw new \Exception("No operation defined for route {$route}");
        }

        return new $this->registered[$route];
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->registered;
    }
}