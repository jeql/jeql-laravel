<?php

namespace Jeql\Exceptions;

class AppException extends SafeException
{
    const UNKNOWN = 0;

    protected $group = 'APP';

    // todo This should be extendable
    protected $messages = [
        self::UNKNOWN => 'Something went wrong',
    ];
}
