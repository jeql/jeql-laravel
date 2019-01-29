<?php

namespace Jeql\Contracts;

interface Request
{
    /**
     * @return void
     * @throws \Exception
     */
    public function validate();
}