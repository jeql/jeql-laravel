<?php

namespace Jeql\Exceptions;

use Illuminate\Support\Arr;

class Formatter
{
    const DEFAULT_EXCEPTION_GROUP = 'NATIVE';

    /**
     * @param \Throwable $exception
     * @param bool $debug
     *
     * @return \Jeql\Exceptions\Error
     */
    public function format(\Throwable $exception, bool $debug = false): Error
    {
        $error = new Error($this->resolveExceptionCode($exception), $exception->getMessage());

        if ($debug) {
            $error->setDetail('exception.trace', array_map(function($item) {
                return Arr::only($item, ['file', 'line', 'function', 'class']);
            }, $exception->getTrace()));

            if ($previous = $exception->getPrevious()) {

                // Recursively add all nested previous exceptions
                $error->setDetail('exception.previous', $this->format($previous, $debug));
            }
        }

        if ($exception instanceof ContainsDetails) {
            foreach ($exception->getDetails() as $key => $value) {
                $error->setDetail($key, $value);
            }
        }

        return $error;
    }

    /**
     * @param \Throwable $exception
     *
     * @return mixed
     */
    protected function resolveExceptionCode(\Throwable $exception): string
    {
        return $this->resolveExceptionGroup($exception) . ':' . $exception->getCode();
    }

    /**
     * @param \Throwable $exception
     *
     * @return string
     */
    protected function resolveExceptionGroup(\Throwable $exception): string
    {
        if ($exception instanceof SafeException) {
            return $exception->getGroup();
        }

        return static::DEFAULT_EXCEPTION_GROUP;
    }
}
