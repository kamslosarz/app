<?php

namespace App\Response;

class ListResponse extends JsonResponse
{
    public function __construct(array $items, PaginationResponse $paginationResponse)
    {
        parent::__construct([
            'items' => $items,
            'pagination' => $paginationResponse->__toArray()
        ]);
    }
}