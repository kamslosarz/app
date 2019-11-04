<?php

namespace Response;

class Response
{
    protected $headers;
    protected $code;
    protected $contents;

    public function __construct(string $contents = '', array $headers = [], $code = 200)
    {
        $this->headers = $headers;
        $this->code = $code;
        $this->contents = $contents;
    }

    public function __invoke(): void
    {
        foreach($this->headers as $header)
        {
            Header($header);
        }
        http_response_code($this->code);

        echo $this->contents;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}