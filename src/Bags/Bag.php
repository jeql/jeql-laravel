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
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->bag);
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

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return count($this->bag) > 0;
    }
}