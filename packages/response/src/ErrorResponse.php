<?php

namespace Response;

use Exception;

class ErrorResponse extends Response
{
    /**
     * @var Exception
     */
    private $exception;

    public function setException(Exception $exception)
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