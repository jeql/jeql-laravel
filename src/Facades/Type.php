<?php

namespace Jeql\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \Jeql\Type
 */
class Type extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'jeql.type';
    }
}
