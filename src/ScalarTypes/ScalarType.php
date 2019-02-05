<?php

namespace Jeql\ScalarTypes;

abstract class ScalarType
{
    /** @var array */
    protected $rules = [];

    /** @var bool */
    protected $optional = false;

    /** @var string */
    protected $message = 'The value %s is not confirm the defined type';

    /**
     * @param $value
     *
     * @return bool
     */
    abstract protected function isValid($value): bool;

    /**
     * @param $value
     *
     * @throws \Exception
     */
    public function validate($value)
    {
        if ($this->optional && $value === null) {
            return;
        }

        if (!$this->isValid($value)) {
            throw new \Exception(sprintf($this->message,$value));
        }
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