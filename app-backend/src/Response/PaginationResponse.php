<?php

namespace App\Response;

use Collection\ArrayAccessible;

class PaginationResponse extends ArrayAccessible
{
    public function __construct(int $perPage, int $itemsCount, int $offset, int $total)
    {
        parent::__construct([
            'perPage' => $perPage,
            'itemsCount' => $itemsCount,
            'offset' => $offset,
            'total' => $total,
            'page' => floor($offset / $perPage)
        ]);
    }
}