<?php

namespace Jeql\Exceptions;

class ClientException extends SafeException
{
    const UNKNOWN = 0;
    const VALIDATION = 1;

    protected $group = 'CLIENT';

    protected $messages = [
        self::UNKNOWN => 'An unknown client exception occurred.',
        self::VALIDATION => 'Given input failed to pass validation.',
    ];
}
