<?php

namespace Jeql\Bags;

class OutputBag extends Bag
{
    /**
     * OutputBag constructor.
     *
     * @param array $outputs
     */
    public function __construct(array $outputs)
    {
        $formattedOutputs = array_map(function ($output) {
            if (is_array($output)) {
                return new ArgumentBag($output);
            }
        }, $outputs);

        parent::__construct($formattedOutputs);
    }
}