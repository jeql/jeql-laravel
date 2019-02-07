<?php

namespace Jeql\Bags;

use Illuminate\Contracts\Support\Arrayable;

abstract class Bag implements Arrayable
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
        $context = $this->bag;
        $value = null;

        foreach (explode('.', $key) as $segment) {
            $value = $context[$segment] ?? null;

            if ($value === null) {
                break;
            }

            if ($value instanceof Arrayable) {
                $context = $value->toArray();
            }
        }

        return $value ?? $default;
    }

    /**
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return count($this->bag) > 0;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }
}