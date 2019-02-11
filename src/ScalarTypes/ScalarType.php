<?php

namespace Jeql\ScalarTypes;

use Jeql\Bags\ArgumentBag;
use \Jeql\Contracts\ScalarType as ScalarTypeContract;

abstract class ScalarType implements ScalarTypeContract
{
    /** @var array */
    protected $rules = [];

    /** @var bool */
    protected $optional = false;

    /**
     * @param mixed $value
     *
     * @return bool
     */
    abstract protected function isValid($value): bool;

    /**
     * @param string $attribute
     * @param $value
     *
     * @return void
     * @throws \Exception
     */
    public function validate(string $attribute, $value)
    {
        if ($this->optional && $value === null) {
            return;
        }

        if (!$this->isValid($value)) {
            throw new \Exception(str_replace(':attribute', $attribute, $this->message()));
        }
    }

    /**
     * @return string
     */
    protected function message(): string
    {
        return ':attribute is invalid';
    }

    /**
     * Format the given value for specified format
     *
     * @param mixed $value
     * @param ArgumentBag $arguments
     *
     * @return mixed
     */
    public function format($value, ArgumentBag $arguments)
    {
        return $value;
    }

    /**
     * @param array $rules
     *
     * @return $this
     */
    public function rules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return $this
     */
    public function optional(): self
    {
        $this->optional = true;

        return $this;
    }
}