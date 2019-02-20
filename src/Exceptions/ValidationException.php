<?php

namespace Jeql\Exceptions;

use Illuminate\Contracts\Validation\Validator;

class ValidationException extends ClientException implements ContainsDetails
{
    /** @var \Illuminate\Contracts\Validation\Validator */
    protected $validator;

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @param \Throwable|null $previous
     */
    public function __construct(Validator $validator, \Throwable $previous = null)
    {
        $this->validator = $validator;

        parent::__construct(static::VALIDATION, $previous);
    }

    /**
     * @return array
     */
    public function getDetails(): array
    {
        return [
            'validation' => [
                'messages' => $this->validator->errors()->all(),
            ],
        ];
    }
}
