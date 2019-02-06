<?php

namespace Jeql\Bags;

class ArgumentBag extends Bag
{
    /**
     * ArgumentBag constructor.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $formattedArguments = array_map(function ($argument) {
            return is_array($argument) ? new ArgumentBag($argument) : $argument;
        }, $arguments);

        parent::__construct($formattedArguments);
    }
}