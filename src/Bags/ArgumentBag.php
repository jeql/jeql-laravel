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
            if (is_array($argument)) {
                return new ArgumentBag($argument);
            }
        }, $arguments);

        parent::__construct($formattedArguments);
    }
}