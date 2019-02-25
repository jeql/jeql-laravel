<?php

namespace Jeql\Exceptions;

class UnknownAppException extends AppException
{
    /**
     * @param \Throwable $previous
     */
    public function __construct(\Throwable $previous)
    {
        parent::__construct(static::UNKNOWN, $previous);
    }
}
