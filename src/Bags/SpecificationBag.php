<?php

namespace Jeql\Bags;

class SpecificationBag extends Bag
{
    /**
     * SpecificationBag constructor.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $items = array_map(function ($item) {
            return is_array($item) ? new self($item) : $item;
        }, $items);

        parent::__construct($items);
    }
}