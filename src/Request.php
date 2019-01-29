<?php

namespace Jeql;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;
use Jeql\Contracts\Request as RequestContract;

abstract class Request implements RequestContract
{
    /** @var \Illuminate\Http\Request */
    protected $httpRequest;

    /**
     * @param \Illuminate\Http\Request $httpRequest
     */
    public function __construct(HttpRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $key = "arguments.{$key}";

        return $this->httpRequest->json($key, $default);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->httpRequest->json('arguments') ?? [];
    }

    /**
     * @return mixed|array todo InputObjectDefinitionThingy
     */
    protected function arguments()
    {
        return [];
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function validate()
    {
        $this->validateSyntax();

        $this->validateArguments();
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function validateSyntax()
    {
        $rules = [
            'arguments' => ['array'],
        ];

        $validator = Validator::make($this->all(), $rules);

        if ($validator->fails()) {

            // todo EXCEPTION HANDLING
            throw new \Exception('Syntax validation failed.');
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function validateArguments()
    {
        $rules = array_map(function($item) {
            return $item->rules();
        }, $this->arguments());

        $validator = Validator::make($this->all(), $rules);

        if ($validator->fails()) {

            // todo EXCEPTION HANDLING
            throw new \Exception('Argument validation failed.');
        }
    }
}
