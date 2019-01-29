<?php

namespace Jeql;

use Illuminate\Validation\Rule;
use Jeql\Rules\Integer;

class Type
{
    /** @var array */
    protected $rules = ['required'];

    /**
     * @return $this
     */
    public function string(): self
    {
        $this->rules = array_merge($this->rules,['string']);

        return $this;
    }

    /**
     * @return $this
     */
    public function email(): self
    {
        $this->rules = array_merge($this->rules,['string', 'email']);

        return $this;
    }

    /**
     * @return $this
     */
    public function password(): self
    {
        $this->rules = array_merge($this->rules,['string', 'min:6']);

        return $this;
    }

    /**
     * @return $this
     */
    public function integer(): self
    {
        $this->rules = array_merge($this->rules,[new Integer]);

        return $this;
    }

    /**
     * Alias of the `integer()` method.
     *
     * @return $this
     */
    public function int(): self
    {
        return $this->integer();
    }

    /**
     * @return $this
     */
    public function float(): self
    {
        $this->rules = array_merge($this->rules,['float']);

        return $this;
    }

    /**
     * @return $this
     */
    public function nullable(): self
    {
        unset($this->rules[array_search('required', $this->rules)]);

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function enum(array $values): self
    {
        $this->rules = array_merge($this->rules,[
            Rule::in($values),
        ]);

        return $this;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return $this->rules;
    }
}