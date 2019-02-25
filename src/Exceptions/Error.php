<?php

namespace Jeql\Exceptions;

use Illuminate\Support\Arr;

class Error implements \JsonSerializable
{
    /** @var mixed */
    protected $code;

    /** @var string */
    protected $message;

    /** @var array */
    protected $details;

    /**
     * @param mixed $code
     * @param string $message
     */
    public function __construct($code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
        $this->details = [];
    }

    /**
     * Add a detail to the error.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return \Jeql\Exceptions\Error
     */
    public function setDetail(string $key, $value): Error
    {
        Arr::set($this->details, $key, $value);

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'details' => $this->details,
        ];
    }
}
