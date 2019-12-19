<?php

namespace App\Response;

class JsonResponse
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}