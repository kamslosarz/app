<?php

namespace App\Response;

class JsonResponse
{
    const STATUS_OK = true;
    const STATUS_ERROR = false;

    protected array $data;
    protected bool $success = true;
    protected array $errors = [];

    public function __construct(array $data = [], bool $success = self::STATUS_OK, array $errors = [])
    {
        $this->data = $data;
        $this->success = $success;
        $this->errors = $errors;
    }

    public function toJson(): string
    {
        return json_encode([
            'success' => $this->success,
            'errors' => $this->errors,
            'data' => $this->data
        ]);
    }
}