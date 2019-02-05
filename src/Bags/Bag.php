<?php

namespace Jeql\Bags;

abstract class Bag
{
    /** @var array  */
    protected $bag = [];

    /**
     * @param array $items
     *
     * @return void
     */
    public function __construct(array $items)
    {
        $this->bag = $items;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->bag;
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->bag[$key] ?? $default;
    }
}