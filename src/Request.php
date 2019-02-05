<?php

namespace Jeql;

use Illuminate\Http\Request as HttpRequest;
use Jeql\Bags\ArgumentBag;
use Jeql\Bags\RequestedFieldBag;

class Request extends Context
{
    /** @var ArgumentBag */
    protected $argumentBag;

    /** @var RequestedFieldBag  */
    protected $requestedFieldBag;

    /**
     * Request constructor.
     *
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $arguments = $request->json('arguments');
        $fields = $request->json('fields');

        $this->argumentBag = new ArgumentBag($arguments);
        $this->fieldBag = new RequestedFieldBag($fields);
    }

    public function getArguments()
    {
        return $this->argumentBag;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArgument(string $key)
    {
        return $this->argumentBag()->get($key);
    }

    /**
     * @return RequestedFieldBag
     */
    public function getFields(): RequestedFieldBag
    {
        return $this->requestedFieldBag;
    }

    /**
     * @param string $key
     *
     * @return RequestedField|mixed|null
     */
    public function getField(string $key)
    {
        return $this->getFields()->get($key);
    }
}