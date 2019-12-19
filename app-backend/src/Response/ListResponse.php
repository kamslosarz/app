<?php

namespace App\Response;

class ListResponse extends JsonResponse
{
    public function toJson(): string
    {
        return json_encode(['items' => $this->data]);
    }
}