<?php

namespace Jeql\Exceptions;

abstract class SafeException extends \Exception
{
    /** @var string */
    protected $group = '';

    /** @var array */
    protected $messages = [];

    /**
     * @param mixed $code
     * @param \Throwable|null $previous
     */
    public function __construct(int $code = 0, \Throwable $previous = null)
    {
        $message = $this->resolveMessageForErrorCode($code);

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param mixed $errorCode
     *
     * @return string
     */
    protected function resolveMessageForErrorCode($errorCode): string
    {
        return $this->messages[$errorCode] ?? '';
    }
}
