<?php

namespace Response;

use Exception;
use Throwable;

class ErrorResponse extends Response
{
    private Throwable $exception;

    public function setException(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function __invoke(): void
    {
        $this->code = 500;
        if($this->exception instanceof Exception)
        {
            $this->contents = $this->exception->getMessage().PHP_EOL;
            $this->contents .= $this->exception->getTraceAsString();
        }

        parent::__invoke();
    }
}